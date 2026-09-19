<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\VisitSchedule;

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
     * Grace period after a visit's date before a never-completed visit is
     * treated as missed, so the admin has a short window to mark it
     * completed for a customer who did attend before this sweeps it away.
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
     * A venue visit still sitting in 'pending'/'confirmed' well past its date
     * was never marked completed by the admin — treated as a no-show per the
     * required-visit policy, regardless of the booking's payment status.
     */
    public static function cancelMissedVisits(): int
    {
        $count = 0;
        $cutoff = now()->subDays(self::VISIT_MISSED_GRACE_DAYS);

        $visits = VisitSchedule::with('booking')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('visit_date', '<', $cutoff)
            ->get();

        foreach ($visits as $visit) {
            $booking = $visit->booking;

            if (!$booking || $booking->hasFinalStatus()) {
                continue;
            }

            $visit->status = 'missed';
            $visit->save();

            $booking->cancellation_reason = 'Automatically cancelled — the required venue visit was missed.';
            $booking->save();

            if (BookingStatusService::transition($booking, Booking::STATUS_CANCELLED)) {
                $count++;
            }
        }

        return $count;
    }
}
