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

    /** A dead booking — nothing further (payment, receipt) should act on it. */
    public function isCancelledOrRejected(): bool
    {
        return in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_REJECTED], true);
    }

    /**
     * "08:00 AM – 04:00 PM", with "(next day)" when the end time is not after
     * the start (overnight packages, e.g. Night Swim 7:00 PM – 7:00 AM).
     * Null while no schedule is set.
     */
    public function scheduleLabel(string $separator = ' – '): ?string
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        return $start->format('h:i A') . $separator . $end->format('h:i A')
            . ($end->format('H:i') <= $start->format('H:i') ? ' (next day)' : '');
    }

    /**
     * A customer may delete (hide) a booking from "My Reservations" when it
     * didn't go ahead: cancelled or rejected, or its date passed without
     * being completed. Completed bookings stay as the customer's history,
     * and upcoming ones must be cancelled first.
     */
    public function customerCanDelete(): bool
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return false;
        }

        return $this->isCancelledOrRejected() || $this->event_date->lt(now()->startOfDay());
    }

    /**
     * Fixed physical room numbers behind each room-type package. Lives here
     * as code (not a `rooms` table/model) since the list is static and not
     * admin-editable — avoids a second, parallel room-booking system.
     */
    public const ROOM_GROUPS = [
        'Standard Room'  => ['104', '304'],
        'Family Room'    => ['101', '102', '301', '302'],
        'Dormitory Room' => ['103', '303'],
    ];

    /**
     * Packages listed under "Swimming" in the booking form's package list.
     * Every other non-room package (including new ones an admin adds) is
     * listed under "Event".
     */
    public const SWIM_PACKAGES = ['Day Swim', 'Night Swim', '22 Hours Overnight'];

    /** Booking form "Occasion" choices (value => label). */
    public const OCCASIONS = [
        'swimming'   => 'Swimming',
        'staycation' => 'Staycation',
        'event'      => 'Event',
    ];

    /**
     * Which occasion a package belongs to: rooms are Staycation, the
     * SWIM_PACKAGES are Swimming, everything else is an Event package.
     */
    public static function occasionForPackage(string $package): string
    {
        if (static::isRoomPackage($package)) {
            return 'staycation';
        }

        return in_array($package, self::SWIM_PACKAGES, true) ? 'swimming' : 'event';
    }

    /** Photo for a package (same images as the Discover page; pool shot for swimming). */
    public static function packageImage(string $package): string
    {
        return asset('images/' . match ($package) {
            'Standard Room'  => 'StandardRoom.jpg',
            'Family Room'    => 'FamilyRoom.jpg',
            'Dormitory Room' => 'DormitoryRoom.jpg',
            'Venue Hall', 'Full Event Package' => 'Venue Halll.jpg',
            default          => 'LORDANES_BG.jpg',
        });
    }

    public static function isRoomPackage(string $package): bool
    {
        return array_key_exists($package, self::ROOM_GROUPS);
    }

    public static function roomsFor(string $package): array
    {
        return self::ROOM_GROUPS[$package] ?? [];
    }

    /**
     * A room is unavailable once ANY booking for it on that date is anything
     * other than cancelled/rejected — including a still-pending request, not
     * only an admin-approved one. Distinct from the venue-wide "one event per
     * day" rule, which only blocks on 'approved' (see BookingController).
     */
    /**
     * Next booking number for today: LDP-YYYYMMDD-XXXX. Continues from the
     * highest number already issued today rather than counting today's
     * rows — a count goes back down when a booking is deleted and would then
     * hand out a number that's still in use.
     */
    public static function nextBookingNumber(): string
    {
        $prefix = 'LDP-' . now()->format('Ymd') . '-';

        $last = static::where('booking_number', 'like', $prefix . '%')
            ->orderByDesc('booking_number')
            ->value('booking_number');

        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public static function isRoomAvailable(string $package, string $date, string $roomNumber, ?int $excludeId = null): bool
    {
        return !static::where('package', $package)
            ->where('room_number', $roomNumber)
            ->where('event_date', $date)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_REJECTED])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * A non-room package (Swimming / Event) can be held by only one active
     * booking per date — pending counts, so the first booking gets it — the
     * same rule rooms follow per room. Other packages that day are unaffected.
     */
    public static function isPackageAvailable(string $package, string $date, ?int $excludeId = null): bool
    {
        return !static::where('package', $package)
            ->whereNull('room_number')
            ->where('event_date', $date)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_REJECTED])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /** Whether this exact package (and room) is free on a date, ignoring this booking. */
    public function slotAvailableOn(string $date): bool
    {
        return $this->room_number
            ? static::isRoomAvailable($this->package, $date, $this->room_number, $this->id)
            : static::isPackageAvailable($this->package, $date, $this->id);
    }

    /**
     * Availability of every room in every room-type package for one date,
     * plus every non-room package under "_packages" (name => bool).
     */
    public static function roomAvailabilityForDate(string $date): array
    {
        $result = [];

        foreach (self::ROOM_GROUPS as $package => $rooms) {
            $result[$package] = [];
            foreach ($rooms as $room) {
                $result[$package][$room] = self::isRoomAvailable($package, $date, $room);
            }
        }

        $taken = static::whereNull('room_number')
            ->where('event_date', $date)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_REJECTED])
            ->pluck('package')
            ->all();

        $result['_packages'] = Package::pluck('name')
            ->reject(fn ($name) => self::isRoomPackage($name))
            ->mapWithKeys(fn ($name) => [$name => !in_array($name, $taken, true)])
            ->all();

        return $result;
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
            self::STATUS_PENDING   => '#B8860B',
            self::STATUS_APPROVED  => '#10b981',
            self::STATUS_ONGOING   => '#3b82f6',
            self::STATUS_COMPLETED => '#B8860B',
            self::STATUS_REJECTED  => '#ef4444',
            self::STATUS_CANCELLED => '#94a3b8',
            default                => '#94a3b8',
        };
    }

    protected $fillable = [
        'user_id',
        'event_type',
        'package',
        'room_number',
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
        'booking_group',
        'reschedule_count',
        'reschedule_status',
        'requested_event_date',
        'requested_visit_date',
        'reschedule_reason',
        'reschedule_fee',
        'cancellation_reason',
    ];

    protected $casts = [
        'event_date' => 'date',
        'down_payment_paid_at' => 'datetime',
        'requested_event_date' => 'date',
        'requested_visit_date' => 'date',
        'hidden_by_customer_at' => 'datetime',
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