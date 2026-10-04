<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Admin pages are hidden from customers in the UI, but that alone doesn't
     * stop a logged-in customer from sending the request directly — every
     * /admin route must be gated by role on the server.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}
