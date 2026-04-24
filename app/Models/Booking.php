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
    ];

    protected $casts = [
        'event_date' => 'date',
        'down_payment_paid_at' => 'datetime',
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