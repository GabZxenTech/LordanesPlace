<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\OtpVerificationController;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('signup');
    }

    public function register(Request $request)
    {
        // Blacklist of commonly used / easily guessable passwords
        $commonPasswords = [
            '12345678', '123456789', '1234567890', '12345678910',
            'password', 'password1', 'password12', 'password123', 'password1234',
            'Password1', 'Password12', 'Password123', 'Password1234',
            'qwerty123', 'qwerty1234', 'qwertyuiop',
            'abcdefgh', 'abcdefghi', 'abcdef123',
            'abc12345', 'abc123456',
            'letmein1', 'welcome1', 'admin123', 'admin1234',
            'iloveyou', 'trustno1', 'sunshine1',
            'football1', 'baseball1', 'dragon123', 'master123',
            'monkey123', 'shadow123', 'princess1',
            'passw0rd', 'p@ssword', 'p@ssw0rd', 'P@ssword1', 'P@ssw0rd1',
            'Qwerty123', 'Qwerty@123', 'Test@1234', 'Welcome@1',
            'changeme', 'changeme1',
        ];

        // An unverified signup (e.g. one whose OTP email never arrived) should
        // never permanently squat on an email address — treat resubmitting
        // registration for it as a fresh retry instead of "already taken".
        // A verified account with that email is untouched by this.
        User::where('email', $request->input('email'))
            ->whereNull('email_verified_at')
            ->delete();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email:dns|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'not_in:' . implode(',', $commonPasswords),
            ],
        ], [
            'name.required'      => 'Full name is required.',
            'email.required'     => 'Email is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'Email is already taken.',
            'password.required'  => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.not_in'    => 'This password is too common. Please choose a stronger password.',
        ]);

        // reCAPTCHA verification
        $recaptcha = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$recaptcha->json('success')) {
            return back()->withErrors(['recaptcha' => 'Please complete the reCAPTCHA.'])->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Generate and send OTP for email verification
        $sent = OtpVerificationController::generateAndSendOtp($user);

        return redirect()->route('otp.show')
            ->with($sent ? [] : ['email_warning' => 'We could not deliver the verification code to your email. Please make sure your email address is correct or try resending.']);
    }

    // ==================== LOGIN ====================
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email:rfc',
            'password' => 'required',
        ], [
            'email.required'    => 'Email is required.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        // Rate limiting: max 5 failed attempts per minute (keyed by email + IP)
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} second(s).",
            ])->onlyInput('email');
        }

        // reCAPTCHA verification
        $recaptcha = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$recaptcha->json('success')) {
            return back()->withErrors(['recaptcha' => 'Please complete the reCAPTCHA.'])->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            // I-update ang last_active
            User::where('id', Auth::id())->update(['last_active' => now()]);

            // Kung admin, redirect sa admin dashboard
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // If email not verified, send OTP and redirect to verification
            if (!Auth::user()->email_verified_at) {
                $sent = OtpVerificationController::generateAndSendOtp(Auth::user());
                return redirect()->route('otp.show')
                    ->with($sent ? [] : ['email_warning' => 'We could not deliver the verification code to your email. Please make sure your email address is correct or try resending.']);
            }

            return redirect()->route('home');
        }

        // Record failed attempt for rate limiting
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    // ==================== PASSWORD RESET ====================
    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Your password has been reset. Please log in with your new password.');
        }

        return back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    // ==================== LOGOUT ====================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ==================== GOOGLE AUTH ====================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, update google_id and avatar if empty
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar()
                    ]);
                }
            } else {
                // Create new user (not yet verified — OTP required)
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user);
            User::where('id', Auth::id())->update(['last_active' => now()]);

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // If email not verified, send OTP and redirect to verification
            if (!Auth::user()->email_verified_at) {
                $sent = OtpVerificationController::generateAndSendOtp(Auth::user());
                return redirect()->route('otp.show')
                    ->with($sent ? [] : ['email_warning' => 'We could not deliver the verification code to your email. Please try resending.']);
            }

            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google Login failed. Please try again.',
            ]);
        }
    }
}