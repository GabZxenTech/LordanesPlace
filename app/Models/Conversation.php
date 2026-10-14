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
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'admin_read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Conversations with a customer/guest message the admin hasn't read yet
     * (sent after admin_read_at, or never opened at all).
     */
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

    /** Number of people (conversations) waiting for the admin to read them. */
    public static function unreadCountForAdmin(): int
    {
        return static::unreadForAdmin()->count();
    }

    public function markReadByAdmin(): void
    {
        $this->forceFill(['admin_read_at' => now()])->save();
    }
}
