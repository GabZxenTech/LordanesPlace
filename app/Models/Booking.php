<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * Required down payment as a fraction of the total. Single source of truth —
     * change it here and every calculation and label follows.
     */
    public const DOWN_PAYMENT_RATE = 0.25;

    /** The rate as a whole number, for labels like "Down Payment (25%)". */
    public static function downPaymentRatePercent(): int
    {
        return (int) round(self::DOWN_PAYMENT_RATE * 100);
    }

    /** Down payment owed on a given total. */
    public static function calculateDownPayment(float $total): float
    {
        return round($total * self::DOWN_PAYMENT_RATE, 2);
    }

    /**
     * The percentage THIS booking's down payment actually represents.
     *
     * Derived from the stored amounts rather than the current rate, so receipts
     * for bookings made under an older rate keep showing the figure the customer
     * was originally quoted.
     */
    public function downPaymentPercent(): int
    {
        if (!$this->total_amount || $this->total_amount <= 0) {
            return self::downPaymentRatePercent();
        }

        return (int) round(($this->down_payment_amount / $this->total_amount) * 100);
    }

    public const PAYMENT_OPTION_FULL        = 'full_payment';
    public const PAYMENT_OPTION_DOWNPAYMENT = 'downpayment';

    /** Every payment arrangement a customer may choose at booking time. */
    public const PAYMENT_OPTIONS = [
        self::PAYMENT_OPTION_FULL,
        self::PAYMENT_OPTION_DOWNPAYMENT,
    ];

    public static function paymentOptionLabel(?string $option): string
    {
        return match ($option) {
            self::PAYMENT_OPTION_FULL => 'Full Payment',
            self::PAYMENT_OPTION_DOWNPAYMENT => self::downPaymentRatePercent() . '% Downpayment',
            default => 'Not selected',
        };
    }

    /**
     * Amount the customer is expected to pay upfront, purely a display/summary
     * figure derived from the chosen payment option — this is an intent the
     * customer selected, NOT a record of money actually received. Whether it
     * was paid is still tracked separately by `payment_status`.
     */
    public function amountToPay(): float
    {
        return $this->payment_option === self::PAYMENT_OPTION_FULL
            ? (float) $this->total_amount
            : (float) $this->down_payment_amount;
    }

    public function remainingBalanceAtBooking(): float
    {
        return round((float) $this->total_amount - $this->amountToPay(), 2);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Actual amount an admin has confirmed as received (sum of the payments
     * ledger) — this is real money collected, never the customer's stated
     * payment_option intent.
     */
    public function amountPaid(): float
    {
        return (float) ($this->relationLoaded('payments')
            ? $this->payments->sum('amount')
            : $this->payments()->sum('amount'));
    }

    /** Actual outstanding balance based on confirmed payments. */
    public function remainingBalance(): float
    {
        return round((float) $this->total_amount - $this->amountPaid(), 2);
    }

    /**
     * True once at least one payment has been admin-confirmed. This is the
     * single gate that decides whether the official receipt is available —
     * selecting a payment option at booking time never satisfies it.
     */
    public function hasConfirmedPayment(): bool
    {
        return $this->amountPaid() > 0;
    }

    public const STATUS_PENDING   = 'pending';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_ONGOING   = 'ongoing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REJECTED  = 'rejected';

    /** Every status the booking workflow recognises. */
    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_ONGOING,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
        self::STATUS_REJECTED,
    ];

    /**
     * Which statuses each status may move to. Anything not listed is an invalid
     * transition; the three terminal states cannot move at all. A status is
     * never allowed to transition to itself, which is what stops a repeated
     * update from creating a duplicate notification.
     */
    protected const TRANSITIONS = [
        self::STATUS_PENDING   => [self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_CANCELLED],
        self::STATUS_APPROVED  => [self::STATUS_ONGOING, self::STATUS_COMPLETED, self::STATUS_CANCELLED],
        self::STATUS_ONGOING   => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
        self::STATUS_COMPLETED => [],
        self::STATUS_CANCELLED => [],
        self::STATUS_REJECTED  => [],
    ];

    /** Statuses this booking may legally move to right now. */
    public function allowedTransitions(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }

    /** True once the booking has reached a state it can never leave. */
    public function hasFinalStatus(): bool
    {
        return $this->allowedTransitions() === [];
    }

    /** Single source of truth for status badge colour across the whole system. */
    public static function statusColor(?string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING   => '#f59e0b',
            self::STATUS_APPROVED  => '#10b981',
            self::STATUS_ONGOING   => '#3b82f6',
            self::STATUS_COMPLETED => '#a88a4c',
            self::STATUS_REJECTED  => '#ef4444',
            self::STATUS_CANCELLED => '#94a3b8',
            default                => '#94a3b8',
        };
    }

    protected $fillable = [
        'user_id',
        'event_type',
        'package',
        'event_date',
        'start_time',
        'end_time',
        'guest_count',
        'notes',
        'status',
        'total_amount',
        'down_payment_amount',
        'payment_option',
        'payment_status',
        'down_payment_paid_at',
        'booking_number',
        'reschedule_count',
        'reschedule_status',
        'requested_event_date',
        'requested_visit_date',
        'reschedule_reason',
        'reschedule_fee',
    ];

    protected $casts = [
        'event_date' => 'date',
        'down_payment_paid_at' => 'datetime',
        'requested_event_date' => 'date',
        'requested_visit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visitSchedules()
    {
        return $this->hasMany(VisitSchedule::class);
    }
}