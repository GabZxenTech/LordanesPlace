<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use App\Models\Payment;
use App\Models\VisitSchedule;
use App\Mail\BookingUpdateMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    // Only the important updates are also sent by email.
    protected const EMAIL_TYPES = [
        'booking_pending', 'booking_approved', 'booking_rejected', 'booking_ongoing',
        'booking_completed', 'booking_cancelled', 'payment_received', 'payment_completed',
    ];

    protected static function toCustomer(Booking $booking, string $type, string $title, string $message, bool $email = true): void
    {
        Notification::create([
            'recipient_type' => 'customer',
            'recipient_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);

        if ($email && in_array($type, self::EMAIL_TYPES, true)) {
            self::emailCustomer($booking, $title, $message);
        }
    }

    // Queues the email; a mail problem must never break the booking itself.
    protected static function emailCustomer(Booking $booking, string $title, string $message): void
    {
        try {
            $user = $booking->user;
            if (!$user || !$user->email) {
                return;
            }

            $peso = fn (float $n) => '₱' . number_format($n, 2);
            $paid = $booking->amountPaid();

            // Any email about a fully paid booking carries its receipt.
            $withReceipt = $paid > 0
                && ((float) $booking->total_amount - $paid) <= 0.01
                && !$booking->isCancelledOrRejected();

            Mail::to($user->email)->queue(new BookingUpdateMail($user->name, $title, $message, [
                'number' => $booking->booking_number,
                'package' => $booking->package . ($booking->room_number ? ' ' . $booking->room_number : ''),
                'date' => $booking->event_date->format('F d, Y'),
                'status' => ucfirst($booking->status),
                'total' => $peso((float) $booking->total_amount),
                'paid' => $peso($paid),
                'balance' => $peso(max(0, (float) $booking->total_amount - $paid)),
                'receiptUrl' => $withReceipt ? route('booking.receipt', $booking->id) : null,
                'receiptBookingId' => $withReceipt ? $booking->id : null,
            ]));
        } catch (\Throwable $e) {
            Log::warning('Booking email not queued: ' . $e->getMessage());
        }
    }

    // Tells the customer a payment was recorded (or that the booking is fully paid).
    public static function paymentRecorded(Booking $booking, Payment $payment): void
    {
        $amount = '₱' . number_format((float) $payment->amount, 2);

        if ($booking->remainingBalance() <= 0.01) {
            self::toCustomer(
                $booking,
                'payment_completed',
                'Payment Complete',
                "We received your payment of {$amount}. Your booking is now fully paid. Thank you!"
            );
            return;
        }

        $remaining = '₱' . number_format($booking->remainingBalance(), 2);
        self::toCustomer(
            $booking,
            'payment_received',
            'Payment Received',
            "We received your payment of {$amount}. Your remaining balance is {$remaining}."
        );
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
    public static function bookingStatusChanged(Booking $booking, bool $email = true): void
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

        self::toCustomer($booking, 'booking_' . $booking->status, $title, $message, $email);
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
