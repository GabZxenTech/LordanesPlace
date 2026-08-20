<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use App\Mail\EmailOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function show()
    {
        $user = Auth::user();

        // Already verified — go home
        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        return view('auth.verify-email', [
            'maskedEmail' => self::maskEmail($user->email),
        ]);
    }

    /**
     * Verify the submitted OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.size'     => 'The verification code must be exactly 6 digits.',
        ]);

        $user = Auth::user();

        // Already verified
        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        // Get the latest unused OTP for this user
        $otpRecord = EmailOtp::where('user_id', $user->id)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'otp' => 'No verification code found. Please request a new one.',
            ]);
        }

        // Check if expired
        if ($otpRecord->isExpired()) {
            return back()->withErrors([
                'otp' => 'This verification code has expired. Please request a new one.',
            ]);
        }

        // Verify OTP via hash comparison
        if (!Hash::check($request->otp, $otpRecord->otp)) {
            return back()->withErrors([
                'otp' => 'The verification code you entered is incorrect. Please try again.',
            ]);
        }

        // Mark OTP as used (single use)
        $otpRecord->markUsed();

        // Mark email as verified
        $user->update(['email_verified_at' => now()]);

        return redirect()->route('home')
            ->with('success', 'Your email has been verified successfully!');
    }

    /**
     * Resend a new OTP (rate-limited).
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        // Already verified
        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        // Rate limit: max 3 OTP requests per 5 minutes per user
        $rateLimitKey = 'otp-resend:' . $user->id;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'resend' => "Too many requests. Please wait {$minutes} minute(s) before requesting a new code.",
            ]);
        }

        // Cooldown: 60 seconds between consecutive requests
        $cooldownKey = 'otp-cooldown:' . $user->id;

        if (RateLimiter::tooManyAttempts($cooldownKey, 1)) {
            $seconds = RateLimiter::availableIn($cooldownKey);

            return back()->withErrors([
                'resend' => "Please wait {$seconds} second(s) before requesting a new code.",
            ]);
        }

        // Invalidate all previous unused OTPs for this user
        EmailOtp::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Generate and send new OTP
        self::generateAndSendOtp($user);

        // Record rate-limit hits
        RateLimiter::hit($rateLimitKey, 300);  // 5-minute window
        RateLimiter::hit($cooldownKey, 60);    // 60-second cooldown

        return back()->with('message', 'A new verification code has been sent to your email.');
    }

    /**
     * Generate a 6-digit OTP, store its hash, and send the plaintext via email.
     */
    public static function generateAndSendOtp($user): bool
    {
        // Generate cryptographically secure 6-digit code
        $plainOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store hashed — never store plaintext in DB
        EmailOtp::create([
            'user_id'    => $user->id,
            'otp'        => Hash::make($plainOtp),
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send via configured SMTP — wrapped in try-catch so registration
        // still completes even if the email fails to deliver.
        try {
            Mail::to($user->email)->send(new EmailOtpMail($plainOtp, $user->name));
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mask an email address for display (e.g., p*************s@gmail.com).
     */
    public static function maskEmail(string $email): string
    {
        $parts  = explode('@', $email);
        $name   = $parts[0];
        $domain = $parts[1];

        if (strlen($name) <= 2) {
            $masked = $name[0] . '***';
        } else {
            $masked = $name[0] . str_repeat('*', strlen($name) - 2) . substr($name, -1);
        }

        return $masked . '@' . $domain;
    }
}
