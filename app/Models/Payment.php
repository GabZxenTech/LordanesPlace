<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One confirmed payment against a booking. Bookings can have several rows
 * (an initial downpayment plus later additional payments) — the sum of a
 * booking's payments is always the authoritative "amount paid".
 */
class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'confirmed_by',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
