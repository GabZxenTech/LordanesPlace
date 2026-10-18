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
        ];
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
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
