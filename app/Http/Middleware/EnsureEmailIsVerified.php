<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Not authenticated — let the auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Admin users bypass email verification
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Redirect unverified users to OTP page
        if (!$user->email_verified_at) {
            return redirect()->route('otp.show');
        }

        return $next($request);
    }
}
