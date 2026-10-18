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

        // Not logged in: the auth middleware handles it.
        if (!$user) {
            return $next($request);
        }

        // Admins skip email verification.
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Unverified users go to the verification page.
        if (!$user->email_verified_at) {
            return redirect()->route('otp.show');
        }

        return $next($request);
    }
}
