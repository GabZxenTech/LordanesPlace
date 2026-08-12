<?php

namespace App\Services;

use App\Models\Booking;

/**
 * Single entry point for every booking status change.
 *
 * Routing all status writes through here guarantees three things the workflow
 * depends on: invalid transitions are rejected, the change is persisted, and
 * exactly one customer notification is created per real change.
 */
class BookingStatusService
{
    /**
     * Attempt to move a booking to a new status.
     *
     * Returns true only when the status actually changed. Re-applying the
     * current status is a no-op (no write, no notification), which is what
     * prevents duplicate notifications.
     */
    public static function transition(Booking $booking, string $status): bool
    {
        if (!in_array($status, Booking::STATUSES, true)) {
            return false;
        }

        // Same status requested again -> nothing happened, so notify nobody.
        if ($booking->status === $status) {
            return false;
        }

        if (!$booking->canTransitionTo($status)) {
            return false;
        }

        $booking->update(['status' => $status]);

        NotificationService::bookingStatusChanged($booking);

        return true;
    }

    /**
     * Human-readable reason a transition was refused, for flashing back to the
     * admin or customer who attempted it.
     */
    public static function failureReason(Booking $booking, string $status): string
    {
        if (!in_array($status, Booking::STATUSES, true)) {
            return 'Unknown booking status.';
        }

        if ($booking->status === $status) {
            return 'This booking is already marked as ' . $status . '.';
        }

        if ($booking->hasFinalStatus()) {
            return 'This booking is already ' . $booking->status . ' and can no longer be changed.';
        }

        return 'Cannot change a ' . $booking->status . ' booking to ' . $status . '.';
    }
}
