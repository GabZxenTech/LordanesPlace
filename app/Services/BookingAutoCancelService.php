<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\VisitSchedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

// Automatic rules: cancel unpaid bookings on their event day and handle missed visits.
// Runs from page loads (there is no cron in production) and the bookings:auto-cancel command.
class BookingAutoCancelService
{
    // Days a missed visit waits before its booking is cancelled.
    protected const VISIT_MISSED_GRACE_DAYS = 1;

    public static function run(): void
    {
        static::cancelUnpaidPastDue();
        static::cancelMissedVisits();
    }

    // Cancels bookings with no payment once their event date arrives.
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

    // Marks overdue visits as missed, then cancels the booking if still missed after the grace days.
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

            // Skip if the booking has another open or completed visit.
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

    // Runs run() at most once a minute (used by page loads and polling).
    public static function runThrottled(): void
    {
        if (!Cache::add('bookings:auto-sweep', true, 60)) {
            return;
        }

        try {
            static::run();
        } catch (\Throwable $e) {
            // A failure here must not break the page.
            Log::error('Booking auto-sweep failed: ' . $e->getMessage());
        }
    }
}
