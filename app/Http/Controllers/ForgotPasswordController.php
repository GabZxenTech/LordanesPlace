<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Generic response shown regardless of whether the email belongs to an
     * account — never reveal account existence.
     */
    private const GENERIC_SENT_MESSAGE = 'If an account exists for this email address, a verification code has been sent.';

    // ==================== STEP 1: REQUEST CODE ====================

    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email is required.',
            'email.email'    => 'Please enter a valid email address.',
        ]);

        $email = Str::lower($request->input('email'));

        // Rate limit by email + IP so this endpoint can't be used to spam a
        // victim's inbox or enumerate accounts by timing.
        $throttleKey = 'pwreset-send:' . $email . '|' . $request->ip();
        $cooldownKey = 'pwreset-cooldown:' . $email;

        $tooManyRequests = RateLimiter::tooManyAttempts($throttleKey, 3);
        $inCooldown = RateLimiter::tooManyAttempts($cooldownKey, 1);

        // Always remember which email we're verifying, and always land on
        // the same verify-code screen with the same generic message —
        // whether the account exists, was just rate-limited, or not.
        session(['password_reset_email' => $email]);

        if (!$tooManyRequests && !$inCooldown) {
            $user = User::where('email', $email)->first();

            if ($user) {
                self::invalidatePreviousCodes($email);
                self::generateAndSendOtp($email, $user->name);
            }

            RateLimiter::hit($throttleKey, 900);  // 15-minute window
            RateLimiter::hit($cooldownKey, 60);   // 60-second cooldown
        }

        return redirect()->route('password.otp.verify.show')
            ->with('message', self::GENERIC_SENT_MESSAGE);
    }

    // ==================== STEP 2: VERIFY CODE ====================

    public function showVerifyForm()
    {
        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-reset-code', [
            'maskedEmail' => OtpVerificationController::maskEmail($email),
        ]);
    }

    public function verifyCode(Request $request)
    {
        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.size'     => 'The verification code must be exactly 6 digits.',
        ]);

        // Rate limit verification attempts so the 6-digit code can't be brute-forced.
        $attemptKey = 'pwreset-attempt:' . $email;

        if (RateLimiter::tooManyAttempts($attemptKey, 5)) {
            $seconds = RateLimiter::availableIn($attemptKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'otp' => "Too many attempts. Please wait {$minutes} minute(s) and try again.",
            ]);
        }

        $otpRecord = PasswordResetOtp::where('email', $email)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpRecord || !Hash::check($request->input('otp'), $otpRecord->otp)) {
            RateLimiter::hit($attemptKey, 900); // 15-minute window

            return back()->withErrors([
                'otp' => 'Invalid verification code. Please try again.',
            ]);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors([
                'otp' => 'This verification code has expired. Please request a new code.',
            ]);
        }

        // Correct code — single use, and grant access to the reset form.
        $otpRecord->markUsed();
        RateLimiter::clear($attemptKey);

        session()->forget('password_reset_email');
        session(['password_reset_verified_email' => $email]);

        return redirect()->route('password.otp.reset.show');
    }

    public function resendCode(Request $request)
    {
        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $throttleKey = 'pwreset-send:' . $email . '|' . $request->ip();
        $cooldownKey = 'pwreset-cooldown:' . $email;

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'resend' => "Too many requests. Please wait {$minutes} minute(s) before requesting a new code.",
            ]);
        }

        if (RateLimiter::tooManyAttempts($cooldownKey, 1)) {
            $seconds = RateLimiter::availableIn($cooldownKey);

            return back()->withErrors([
                'resend' => "Please wait {$seconds} second(s) before requesting a new code.",
            ]);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            self::invalidatePreviousCodes($email);
            self::generateAndSendOtp($email, $user->name);
        }

        RateLimiter::hit($throttleKey, 900);
        RateLimiter::hit($cooldownKey, 60);

        return back()->with('message', self::GENERIC_SENT_MESSAGE);
    }

    // ==================== STEP 3: RESET PASSWORD ====================

    public function showResetForm()
    {
        $email = session('password_reset_verified_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-otp', [
            'email' => $email,
        ]);
    }

    public function submitReset(Request $request)
    {
        $email = session('password_reset_verified_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();

        // The verified session should always map to a real account, but if
        // it doesn't (e.g. the account was deleted mid-flow), don't proceed.
        if (!$user) {
            session()->forget('password_reset_verified_email');
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'not_in:' . implode(',', AuthController::commonPasswords()),
            ],
        ], [
            'password.required'  => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.not_in'    => 'This password is too common. Please choose a stronger password.',
        ]);

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->save();

        // Invalidate every OTP for this email and the whole reset session —
        // the code and this reset session can never be reused.
        PasswordResetOtp::where('email', $email)->whereNull('used_at')->update(['used_at' => now()]);
        session()->forget(['password_reset_email', 'password_reset_verified_email']);

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully. You can now log in using your new password.');
    }

    // ==================== HELPERS ====================

    private static function invalidatePreviousCodes(string $email): void
    {
        PasswordResetOtp::where('email', $email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);
    }

    private static function generateAndSendOtp(string $email, string $userName): bool
    {
        $plainOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'email'      => $email,
            'otp'        => Hash::make($plainOtp),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($email)->send(new PasswordResetOtpMail($plainOtp, $userName));
            return true;
        } catch (\Exception $e) {
            Log::error('Password reset OTP email failed: ' . $e->getMessage());
            return false;
        }
    }
}
