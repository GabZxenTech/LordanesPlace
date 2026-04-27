<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
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