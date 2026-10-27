<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Down payment rate (25%). Change it here and all calculations follow.
    public const DOWN_PAYMENT_RATE = 0.25;

    // The rate as a whole number, e.g. 25.
    public static function downPaymentRatePercent(): int
    {
        return (int) round(self::DOWN_PAYMENT_RATE * 100);
    }

    // Down payment for a given total.
    public static function calculateDownPayment(float $total): float
    {
        return round($total * self::DOWN_PAYMENT_RATE, 2);
    }

    // Down payment percent of this booking, based on its saved amounts.
    public function downPaymentPercent(): int
    {
        if (!$this->total_amount || $this->total_amount <= 0) {
            return self::downPaymentRatePercent();
        }

        return (int) round(($this->down_payment_amount / $this->total_amount) * 100);
    }

    public const PAYMENT_OPTION_FULL        = 'full_payment';
    public const PAYMENT_OPTION_DOWNPAYMENT = 'downpayment';

    // Payment choices a customer can pick when booking.
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

    // Amount the customer chose to pay upfront (not money actually received).
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

    // Total money the admin has confirmed as received.
    public function amountPaid(): float
    {
        return (float) ($this->relationLoaded('payments')
            ? $this->payments->sum('amount')
            : $this->payments()->sum('amount'));
    }

    // What is still unpaid.
    public function remainingBalance(): float
    {
        return round((float) $this->total_amount - $this->amountPaid(), 2);
    }

    // True once the admin has recorded at least one payment.
    public function hasConfirmedPayment(): bool
    {
        return $this->amountPaid() > 0;
    }

    public function isCancelledOrRejected(): bool
    {
        return in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_REJECTED], true);
    }

    // Time range text, e.g. "07:00 PM – 07:00 AM (next day)". Null if not set.
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

    // Customer can delete (hide) a booking that was cancelled, rejected or already past.
    public function customerCanDelete(): bool
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return false;
        }

        return $this->isCancelledOrRejected() || $this->event_date->lt(now()->startOfDay());
    }

    // Room numbers for each room package.
    public const ROOM_GROUPS = [
        'Standard Room'  => ['104', '304'],
        'Family Room'    => ['101', '102', '301', '302'],
        'Dormitory Room' => ['103', '303'],
    ];

    // Packages shown under "Swimming". Other non-room packages are "Event".
    public const SWIM_PACKAGES = ['Day Swim', 'Night Swim', '22 Hours Overnight'];

    // Occasion choices in the booking form.
    public const OCCASIONS = [
        'swimming'   => 'Swimming',
        'staycation' => 'Staycation',
        'event'      => 'Event',
    ];

    // Which occasion a package belongs to.
    public static function occasionForPackage(string $package): string
    {
        if (static::isRoomPackage($package)) {
            return 'staycation';
        }

        return in_array($package, self::SWIM_PACKAGES, true) ? 'swimming' : 'event';
    }

    // Photo used for a package.
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

    // Next booking number for today: LDP-YYYYMMDD-XXXX.
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

    // A non-room package can only have one active booking per date.
    public static function isPackageAvailable(string $package, string $date, ?int $excludeId = null): bool
    {
        return !static::where('package', $package)
            ->whereNull('room_number')
            ->where('event_date', $date)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_REJECTED])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    // Whether this booking's package (and room) is free on another date.
    public function slotAvailableOn(string $date): bool
    {
        return $this->room_number
            ? static::isRoomAvailable($this->package, $date, $this->room_number, $this->id)
            : static::isPackageAvailable($this->package, $date, $this->id);
    }

    // Availability of every room and package on a date.
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

    // Statuses that take the date (one event per day).
    public const DATE_HOLDING_STATUSES = [self::STATUS_APPROVED, self::STATUS_ONGOING];

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_ONGOING,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
        self::STATUS_REJECTED,
    ];

    // Allowed status changes. Final statuses cannot change.
    protected const TRANSITIONS = [
        self::STATUS_PENDING   => [self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_CANCELLED],
        self::STATUS_APPROVED  => [self::STATUS_ONGOING, self::STATUS_COMPLETED, self::STATUS_CANCELLED],
        self::STATUS_ONGOING   => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
        self::STATUS_COMPLETED => [],
        self::STATUS_CANCELLED => [],
        self::STATUS_REJECTED  => [],
    ];

    // Statuses this booking can move to now.
    public function allowedTransitions(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }

    // True when the booking can no longer change status.
    public function hasFinalStatus(): bool
    {
        return $this->allowedTransitions() === [];
    }

    // Badge colour for a status.
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

    // Includes visits removed from the admin list (they are only hidden).
    public function visitSchedules()
    {
        return $this->hasMany(VisitSchedule::class)->withTrashed();
    }
}