<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    protected static function toCustomer(Booking $booking, string $type, string $title, string $message): void
    {
        Notification::create([
            'recipient_type' => 'customer',
            'recipient_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    protected static function toAdmins(Booking $booking, string $type, string $title, string $message): void
    {
        $adminIds = User::where('role', 'admin')->pluck('id');

        foreach ($adminIds as $adminId) {
            Notification::create([
                'recipient_type' => 'admin',
                'recipient_id' => $adminId,
                'booking_id' => $booking->id,
                'title' => $title,
                'message' => $message,
                'type' => $type,
            ]);
        }
    }

    /**
     * Customer-facing copy for each booking status. Driven by status alone so
     * every path that changes status produces the same wording.
     */
    protected const STATUS_MESSAGES = [
        Booking::STATUS_PENDING => [
            'Booking Pending Review',
            'Your booking request has been received and is currently pending review.',
        ],
        Booking::STATUS_APPROVED => [
            'Booking Approved',
            'Your booking has been approved.',
        ],
        Booking::STATUS_ONGOING => [
            'Event Ongoing',
            'Your event is now ongoing.',
        ],
        Booking::STATUS_COMPLETED => [
            'Booking Completed',
            'Your booking has been completed. Thank you for choosing LorDane\'s Place.',
        ],
        Booking::STATUS_CANCELLED => [
            'Booking Cancelled',
            'Your booking has been cancelled.',
        ],
        Booking::STATUS_REJECTED => [
            'Booking Rejected',
            'Unfortunately, your booking request has been rejected.',
        ],
    ];

    /**
     * Notify the customer that their booking status changed. Always called via
     * BookingStatusService, which guarantees the status really did change, so
     * this never produces a duplicate.
     */
    public static function bookingStatusChanged(Booking $booking): void
    {
        if (!isset(self::STATUS_MESSAGES[$booking->status])) {
            return;
        }

        [$title, $message] = self::STATUS_MESSAGES[$booking->status];

        self::toCustomer($booking, 'booking_' . $booking->status, $title, $message);
    }

    /**
     * A brand new booking: the customer gets the standard "pending" status
     * notification, the admins get an actionable heads-up.
     */
    public static function bookingSubmitted(Booking $booking): void
    {
        self::bookingStatusChanged($booking);

        self::toAdmins(
            $booking,
            'booking_submitted',
            'New Booking Request',
            "{$booking->user->name} submitted a booking request for {$booking->event_date->format('F d, Y')}."
        );
    }

    /**
     * Extra admin-side alert when the CUSTOMER is the one cancelling. The
     * customer's own notification comes from bookingStatusChanged().
     */
    public static function bookingCancelledByCustomer(Booking $booking): void
    {
        self::toAdmins(
            $booking,
            'booking_cancelled',
            'Booking Cancelled',
            "{$booking->user->name} cancelled their reservation for {$booking->event_date->format('F d, Y')}."
        );
    }

    public static function scheduleUpdated(Booking $booking): void
    {
        $start = \Carbon\Carbon::parse($booking->start_time)->format('g:i A');
        $end = \Carbon\Carbon::parse($booking->end_time)->format('g:i A');

        self::toCustomer(
            $booking,
            'schedule_updated',
            'Event Schedule Updated',
            "Your event schedule has been updated.\nDate: {$booking->event_date->format('F d, Y')}\nStart Time: {$start}\nEnd Time: {$end}"
        );
    }

    public static function noteAdded(Booking $booking): void
    {
        self::toCustomer(
            $booking,
            'note_added',
            'New Note on Your Booking',
            "The admin left a note on your booking for {$booking->event_date->format('F d, Y')}: \"{$booking->notes}\""
        );
    }

    public static function rescheduleRequested(Booking $booking): void
    {
        self::toAdmins(
            $booking,
            'reschedule_requested',
            'Reschedule Requested',
            "{$booking->user->name} requested to reschedule their booking currently set for {$booking->event_date->format('F d, Y')}."
        );
    }
}
