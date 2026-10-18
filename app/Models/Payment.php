<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// One payment recorded by the admin for a booking.
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
