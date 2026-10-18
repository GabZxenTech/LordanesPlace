<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'last_message_at',
        'admin_read_at',
        'customer_read_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'admin_read_at' => 'datetime',
        'customer_read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // Conversations with customer messages the admin has not read yet.
    public function scopeUnreadForAdmin(Builder $query): Builder
    {
        return $query->whereHas('messages', function ($q) {
            $q->where('role', 'user')
              ->where(function ($w) {
                  $w->whereNull('conversations.admin_read_at')
                    ->orWhereColumn('messages.created_at', '>', 'conversations.admin_read_at');
              });
        });
    }

    // How many conversations are waiting for the admin.
    public static function unreadCountForAdmin(): int
    {
        return static::unreadForAdmin()->count();
    }

    public function markReadByAdmin(): void
    {
        $this->forceFill(['admin_read_at' => now()])->save();
    }

    // True when the admin replied and the customer has not opened the chat yet.
    public function hasUnreadForCustomer(): bool
    {
        return $this->messages()
            ->where('role', 'admin')
            ->when($this->customer_read_at, fn ($q) => $q->where('created_at', '>', $this->customer_read_at))
            ->exists();
    }

    public function markReadByCustomer(): void
    {
        $this->forceFill(['customer_read_at' => now()])->save();
    }
}
