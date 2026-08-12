<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

/**
 * Single entry point for recording an admin-confirmed payment.
 *
 * Routing every payment write through here guarantees: the amount is sane,
 * a booking that's already fully paid can't receive another payment row (the
 * duplicate-confirmation guard), and payment_status is always recomputed from
 * the actual ledger total rather than set by hand.
 */
class PaymentService
{
    /**
     * Record a payment against a booking and recompute its payment_status.
     *
     * Returns the created Payment on success, or null if the amount was
     * rejected (booking already fully paid, amount <= 0, or amount would
     * overpay the booking).
     */
    public static function recordPayment(Booking $booking, float $amount, ?string $note = null): ?Payment
    {
        if ($amount <= 0) {
            return null;
        }

        // remainingBalance() always issues a fresh query, so this reflects the
        // ledger as it stands right now — the guard against duplicate/extra
        // confirmations for an already-settled booking.
        $remaining = $booking->remainingBalance();

        if ($remaining <= 0) {
            return null;
        }

        // Small epsilon so a legitimate "pay the exact remaining balance"
        // request isn't rejected by float rounding.
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

        // Epsilon guards against float rounding leaving a fully-paid booking
        // stuck at "partially_paid" by a fraction of a centavo.
        if ($totalPaid >= $total - 0.01) {
            return 'fully_paid';
        }

        return 'partially_paid';
    }
}
