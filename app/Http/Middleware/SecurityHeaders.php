<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Baseline browser hardening headers.
     *
     * Deliberately no Content-Security-Policy: the pages rely on inline
     * scripts/handlers, CDN jQuery, Google Fonts and reCAPTCHA, which a CSP
     * would block. X-Frame-Options is SAMEORIGIN (not DENY) because /tour and
     * /discover embed the same-origin 360 tour in an iframe, and fullscreen is
     * left allowed for that tour.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // HSTS only when actually served over HTTPS in production, so local
        // http://localhost development is never pinned to HTTPS.
        if (app()->environment('production') && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }
}
