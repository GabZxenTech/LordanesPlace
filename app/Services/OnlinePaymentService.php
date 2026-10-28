<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Shared by the online payment pages: finds the bookings waiting for payment and records the payment.
class OnlinePaymentService
{
    // The customer's new bookings that are waiting for online payment.
    public static function pendingBookings(): \Illuminate\Support\Collection
    {
        return Booking::where('user_id', Auth::id())
            ->whereIn('id', session('demo_pay_ids', []))
            ->orderBy('id')
            ->get()
            ->filter(fn (Booking $b) => $b->remainingBalance() > 0)
            ->values();
    }

    // Records the payment for each booking, starts them, and notifies the customer.
    // Returns the numbers the success screen needs.
    public static function complete($bookings, string $note): array
    {
        $payments = DB::transaction(function () use ($bookings, $note) {
            $made = [];
            foreach ($bookings as $booking) {
                $amount = min($booking->amountToPay(), $booking->remainingBalance());
                $payment = PaymentService::recordPayment($booking, $amount, $note);
                if ($payment) {
                    $made[] = [$booking, $payment];
                }
            }
            return $made;
        });

        foreach ($payments as [$booking, $payment]) {
            // Paid online: the booking is ongoing right away.
            BookingStatusService::startAfterOnlinePayment($booking->fresh());
            NotificationService::paymentRecorded($booking->fresh(), $payment);
        }

        return [
            'first_id' => $bookings->first()->id,
            'total'    => collect($payments)->sum(fn ($p) => (float) $p[1]->amount),
        ];
    }
}
