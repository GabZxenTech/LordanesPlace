<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'recipient_type',
        'recipient_id',
        'booking_id',
        'title',
        'message',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForRecipient($query, string $recipientType, int $recipientId)
    {
        return $query->where('recipient_type', $recipientType)->where('recipient_id', $recipientId);
    }
}
