<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Pages rendered for a logged-in user must not be stored by the browser.
     * Otherwise, after logout, the Back button shows the cached page (name,
     * bookings, admin panel) as if still signed in — the session is already
     * gone server-side, but the page looks live to whoever uses the device
     * next. no-store makes the browser re-request the page instead, which
     * then redirects to login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user()) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
