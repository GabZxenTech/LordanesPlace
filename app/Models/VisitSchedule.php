<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitSchedule extends Model
{
    use HasFactory;

    // "Remove" in the admin panel only hides a visit (deleted_at is set); the
    // row stays in the database for booking history.
    use SoftDeletes;

    public const STATUS_PENDING     = 'pending';
    public const STATUS_CONFIRMED   = 'confirmed';
    public const STATUS_RESCHEDULED = 'rescheduled';
    public const STATUS_COMPLETED   = 'completed';
    public const STATUS_MISSED      = 'missed';

    /** Every status a visit can have (matches the DB check constraint). */
    public const STATUSES = [
        self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_RESCHEDULED,
        self::STATUS_COMPLETED, self::STATUS_MISSED,
    ];

    /** Visits that are still expected to happen. */
    public const OPEN_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_RESCHEDULED];

    protected $fillable = [
        'booking_id',
        'user_id',
        'visit_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Only a visit still waiting for the admin can be confirmed. */
    public function canConfirm(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Completed is final. A missed visit can still be completed — the admin's
     * correction when the customer did come — until its booking is closed.
     */
    public function canComplete(): bool
    {
        return $this->status !== self::STATUS_COMPLETED && !$this->bookingIsClosed();
    }

    public function canReschedule(): bool
    {
        return $this->status !== self::STATUS_COMPLETED && !$this->bookingIsClosed();
    }

    /**
     * A visit can be removed from the list once it no longer matters for an
     * active reservation: the visit itself is completed, its booking is
     * completed/cancelled/rejected, or the event date has passed. Removing an
     * open visit of an upcoming booking would silently drop its required
     * visit, so that isn't allowed.
     */
    public function canRemove(): bool
    {
        return $this->status === self::STATUS_COMPLETED
            || $this->bookingIsClosed()
            || ($this->booking && $this->booking->event_date->lt(now()->startOfDay()));
    }

    protected function bookingIsClosed(): bool
    {
        return !$this->booking || $this->booking->hasFinalStatus();
    }
}
