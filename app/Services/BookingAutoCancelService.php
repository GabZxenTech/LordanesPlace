<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\VisitSchedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * The two automatic-cancellation rules that don't originate from a customer
 * or admin action: an unpaid booking whose event day has arrived, and a
 * required venue visit the customer never attended. Both route through
 * BookingStatusService::transition() so they get exactly the same
 * status-change notification a manual cancellation would.
 *
 * Called from a scheduled Artisan command (bookings:auto-cancel) AND
 * opportunistically from the pages admins/customers actually load (profile,
 * admin schedule) — this app has no cron running in production, so relying
 * on the schedule alone would mean these rules silently never fire.
 */
class BookingAutoCancelService
{
    /**
     * How long a missed visit can stay missed before its booking is
     * cancelled — the admin's window to mark it completed (the customer did
     * come) or reschedule it.
     */
    protected const VISIT_MISSED_GRACE_DAYS = 1;

    public static function run(): void
    {
        static::cancelUnpaidPastDue();
        static::cancelMissedVisits();
    }

    /**
     * A booking whose event date has arrived (today or already passed) with
     * zero confirmed payment has no reason to keep holding that date.
     */
    public static function cancelUnpaidPastDue(): int
    {
        $count = 0;

        $bookings = Booking::with('payments')
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
            ->whereDate('event_date', '<=', now()->toDateString())
            ->get();

        foreach ($bookings as $booking) {
            if ($booking->hasConfirmedPayment()) {
                continue;
            }

            $booking->cancellation_reason = 'Automatically cancelled — no payment was received by the event date.';
            $booking->save();

            if (BookingStatusService::transition($booking, Booking::STATUS_CANCELLED)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Required venue visits that never happened:
     *  1. An open visit (pending / confirmed / rescheduled) whose scheduled
     *     date and time have passed is marked "missed" right away. Completed
     *     visits are never touched, and a rescheduled visit only counts once
     *     its new time has passed.
     *  2. A visit still "missed" VISIT_MISSED_GRACE_DAYS after its time — the
     *     admin had that window to mark it completed or reschedule it — cancels
     *     its booking, per the required-visit policy.
     * Only rows that actually need a change are queried, so repeated sweeps
     * don't rewrite anything.
     */
    public static function cancelMissedVisits(): int
    {
        VisitSchedule::whereIn('status', VisitSchedule::OPEN_STATUSES)
            ->where('visit_date', '<', now())
            ->update(['status' => VisitSchedule::STATUS_MISSED, 'updated_at' => now()]);

        $count = 0;

        $visits = VisitSchedule::with('booking')
            ->where('status', VisitSchedule::STATUS_MISSED)
            ->where('visit_date', '<', now()->subDays(self::VISIT_MISSED_GRACE_DAYS))
            ->whereHas('booking', fn ($q) => $q->whereNotIn('status', [
                Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED, Booking::STATUS_REJECTED,
            ]))
            ->get();

        foreach ($visits as $visit) {
            $booking = $visit->booking;

            // A later visit for the same booking (e.g. rebooked after the miss)
            // that is still open or completed means the customer is on track.
            $hasActiveVisit = $booking->visitSchedules()
                ->where('id', '!=', $visit->id)
                ->whereIn('status', array_merge(VisitSchedule::OPEN_STATUSES, [VisitSchedule::STATUS_COMPLETED]))
                ->exists();

            if ($booking->hasFinalStatus() || $hasActiveVisit) {
                continue;
            }

            $booking->cancellation_reason = 'Automatically cancelled — the required venue visit was missed.';
            $booking->save();

            if (BookingStatusService::transition($booking, Booking::STATUS_CANCELLED)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * run(), at most once per minute site-wide — for callers that fire often
     * (the 10-second notification bell polling) so overdue visits and unpaid
     * bookings are swept while anyone is using the site, without re-running
     * the queries on every poll. No cron is needed for this path.
     */
    public static function runThrottled(): void
    {
        if (!Cache::add('bookings:auto-sweep', true, 60)) {
            return;
        }

        try {
            static::run();
        } catch (\Throwable $e) {
            // Never let a sweep failure break the request that triggered it.
            Log::error('Booking auto-sweep failed: ' . $e->getMessage());
        }
    }
}
