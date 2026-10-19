<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
        'avatar_image',
    ];

    protected $fillable = [
        'name',
        'email',
        'pending_email',
        'password',
        // 'role' is not mass-assignable so users can't make themselves admin.
        'last_active',
        'is_online',
        'google_id',
        'avatar',
        'email_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'deactivated_at' => 'datetime',
        ];
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    // Profile picture URL: uploaded photo, then Google photo, otherwise null.
    public function avatarUrl(): ?string
    {
        if ($this->avatar_image) {
            return route('account.avatar', ['user' => $this->id, 'v' => substr(md5($this->avatar_image), 0, 8)]);
        }

        return $this->avatar ?: null;
    }

    // Up to two initials from the name, e.g. "Juan Dela Cruz" -> "JD".
    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->name)))->filter()->take(2)
            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('') ?: '?';
    }

    public function emailOtps()
    {
        return $this->hasMany(EmailOtp::class);
    }

    // Sends our own styled password-reset email.
    public function sendPasswordResetNotification($token): void
    {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

        try {
            \Illuminate\Support\Facades\Mail::to($this->email)
                ->send(new \App\Mail\PasswordResetMail($url, $this->name));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Password reset email failed: ' . $e->getMessage());
        }
    }
}
