<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use App\Models\VisitSchedule;

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

    // Customer message for each booking status.
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

    // Tells the customer their booking status changed.
    public static function bookingStatusChanged(Booking $booking): void
    {
        if (!isset(self::STATUS_MESSAGES[$booking->status])) {
            return;
        }

        [$title, $message] = self::STATUS_MESSAGES[$booking->status];

        // Add the admin's reason for rejected or cancelled bookings.
        if (
            in_array($booking->status, [Booking::STATUS_REJECTED, Booking::STATUS_CANCELLED], true)
            && $booking->cancellation_reason
        ) {
            $message .= "\n\nReason: " . $booking->cancellation_reason;
        }

        self::toCustomer($booking, 'booking_' . $booking->status, $title, $message);
    }

    // New booking: notify the customer and the admins.
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

    // Tells the admins that a customer cancelled.
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

    // Visit updates from the admin (sent once per group of bookings made together).
    public static function visitConfirmed(VisitSchedule $visit): void
    {
        self::toCustomer(
            $visit->booking,
            'visit_confirmed',
            'Venue Visit Confirmed',
            "Your venue visit on {$visit->visit_date->format('F d, Y \a\t h:i A')} is confirmed. See you there!"
        );
    }

    public static function visitRescheduled(VisitSchedule $visit): void
    {
        self::toCustomer(
            $visit->booking,
            'visit_rescheduled',
            'Venue Visit Rescheduled',
            "Your venue visit was moved to {$visit->visit_date->format('F d, Y \a\t h:i A')}."
        );
    }

    public static function visitCompleted(VisitSchedule $visit): void
    {
        self::toCustomer(
            $visit->booking,
            'visit_completed',
            'Venue Visit Completed',
            "Thank you for visiting LorDane's Place! Your venue visit on {$visit->visit_date->format('F d, Y')} has been marked as completed."
        );
    }
}
