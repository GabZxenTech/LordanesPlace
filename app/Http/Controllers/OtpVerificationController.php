<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use App\Models\User;
use App\Mail\EmailOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OtpVerificationController extends Controller
{
    // Email verification code page.
    public function show()
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        return view('auth.verify-email', [
            'maskedEmail' => self::maskEmail($user->email),
        ]);
    }

    // Checks the code the user entered.
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.size'     => 'The verification code must be exactly 6 digits.',
        ]);

        $user = Auth::user();

        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        // Limit wrong attempts so the code can't be guessed.
        $attemptKey = 'otp-verify:' . $user->id;

        if (RateLimiter::tooManyAttempts($attemptKey, 5)) {
            $minutes = (int) ceil(RateLimiter::availableIn($attemptKey) / 60);

            return back()->withErrors([
                'otp' => "Too many attempts. Please wait {$minutes} minute(s) and try again.",
            ]);
        }

        // Latest unused code for this user.
        $otpRecord = EmailOtp::where('user_id', $user->id)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'otp' => 'No verification code found. Please request a new one.',
            ]);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors([
                'otp' => 'This verification code has expired. Please request a new one.',
            ]);
        }

        if (!Hash::check($request->otp, $otpRecord->otp)) {
            RateLimiter::hit($attemptKey, 900); // 15-minute window

            // Too many wrong tries: the code is no longer usable.
            if (RateLimiter::tooManyAttempts($attemptKey, 5)) {
                $otpRecord->markUsed();
            }

            return back()->withErrors([
                'otp' => 'The verification code you entered is incorrect. Please try again.',
            ]);
        }

        $otpRecord->markUsed();
        RateLimiter::clear($attemptKey);

        $user->update(['email_verified_at' => now()]);

        return redirect()->route('home')
            ->with('success', 'Your email has been verified successfully!');
    }

    // Sends a new code (rate-limited).
    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        // Max 3 requests per 5 minutes.
        $rateLimitKey = 'otp-resend:' . $user->id;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'resend' => "Too many requests. Please wait {$minutes} minute(s) before requesting a new code.",
            ]);
        }

        // Wait 60 seconds between requests.
        $cooldownKey = 'otp-cooldown:' . $user->id;

        if (RateLimiter::tooManyAttempts($cooldownKey, 1)) {
            $seconds = RateLimiter::availableIn($cooldownKey);

            return back()->withErrors([
                'resend' => "Please wait {$seconds} second(s) before requesting a new code.",
            ]);
        }

        // Old unused codes stop working.
        EmailOtp::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        self::generateAndSendOtp($user);

        RateLimiter::hit($rateLimitKey, 300);  // 5-minute window
        RateLimiter::hit($cooldownKey, 60);    // 60-second cooldown

        return back()->with('message', 'A new verification code has been sent to your email.');
    }

    // Back to the sign up page; the unverified signup is dropped.
    public function back(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && !$user->email_verified_at) {
            $user->delete();
        }

        return redirect()->route('register');
    }

    // Lets an unverified user fix a mistyped email and gets a new code.
    public function changeEmail(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $request->validate([
            'email' => [
                'required',
                'email:dns',
                'max:255',
                Rule::unique('users', 'email')->whereNotNull('email_verified_at'),
            ],
        ], [
            'email.required' => 'Email is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'Email is already taken.',
        ]);

        // Max 3 email changes per 15 minutes.
        $changeKey = 'otp-change-email:' . $user->id;

        if (RateLimiter::tooManyAttempts($changeKey, 3)) {
            $minutes = (int) ceil(RateLimiter::availableIn($changeKey) / 60);

            return back()->withErrors([
                'email' => "Too many email changes. Please wait {$minutes} minute(s) and try again.",
            ]);
        }

        RateLimiter::hit($changeKey, 900);

        // Drop other unverified signups using this email.
        User::where('email', $request->email)
            ->where('id', '!=', $user->id)
            ->whereNull('email_verified_at')
            ->delete();

        $user->update(['email' => $request->email]);

        // Codes sent to the old address stop working.
        EmailOtp::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        RateLimiter::clear('otp-verify:' . $user->id);

        $sent = self::generateAndSendOtp($user);

        // Start the resend cooldown for the new code.
        RateLimiter::hit('otp-cooldown:' . $user->id, 60);

        return redirect()->route('otp.show')
            ->with($sent
                ? ['message' => 'Your email was updated. A new verification code has been sent.']
                : ['email_warning' => 'We could not deliver the verification code to your email. Please make sure your email address is correct or try resending.']);
    }

    // Creates a 6-digit code, saves its hash and emails it.
    public static function generateAndSendOtp($user): bool
    {
        $plainOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Only the hash is saved.
        EmailOtp::create([
            'user_id'    => $user->id,
            'otp'        => Hash::make($plainOtp),
            'expires_at' => now()->addMinutes(5),
        ]);

        // Registration still works even if the email fails.
        try {
            Mail::to($user->email)->send(new EmailOtpMail($plainOtp, $user->name));
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP email failed: ' . $e->getMessage());
            return false;
        }
    }

    // Hides most of an email address, e.g. p*****s@gmail.com.
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
