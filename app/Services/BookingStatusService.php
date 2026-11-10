<?php

namespace App\Services;

use App\Models\Booking;

// Every booking status change goes through here (checks, saves and notifies once).
class BookingStatusService
{
    // Changes the status. Returns true only if it actually changed.
    public static function transition(Booking $booking, string $status): bool
    {
        if (!in_array($status, Booking::STATUSES, true)) {
            return false;
        }

        // Same status again: do nothing.
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

    // Message explaining why a status change was refused.
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
