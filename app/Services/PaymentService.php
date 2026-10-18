<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

// Every payment is recorded through here.
class PaymentService
{
    // Saves a payment and updates the payment status. Returns null if the amount is not allowed.
    public static function recordPayment(Booking $booking, float $amount, ?string $note = null): ?Payment
    {
        if ($amount <= 0) {
            return null;
        }

        // Fresh balance check to block extra payments.
        $remaining = $booking->remainingBalance();

        if ($remaining <= 0) {
            return null;
        }

        // Small allowance for rounding.
        if ($amount > $remaining + 0.01) {
            return null;
        }

        $payment = $booking->payments()->create([
            'amount' => $amount,
            'confirmed_by' => Auth::id(),
            'note' => $note,
        ]);

        $totalPaid = (float) $booking->payments()->sum('amount');

        $booking->update([
            'payment_status' => self::deriveStatus($totalPaid, (float) $booking->total_amount),
            'down_payment_paid_at' => $booking->down_payment_paid_at ?? now(),
        ]);

        return $payment;
    }

    protected static function deriveStatus(float $totalPaid, float $total): string
    {
        if ($totalPaid <= 0) {
            return 'unpaid';
        }

        // Small allowance for rounding.
        if ($totalPaid >= $total - 0.01) {
            return 'fully_paid';
        }

        return 'partially_paid';
    }
}
