<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Sends visitors of any other address (e.g. the old onrender.com link) to the main site address.
class RedirectToCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $canonical = parse_url((string) config('app.url'), PHP_URL_HOST);

        // Only in production, only for browsers loading pages, and never for local addresses.
        if (
            app()->environment('production')
            && $canonical
            && ! in_array($canonical, ['localhost', '127.0.0.1'], true)
            && strcasecmp($request->getHost(), $canonical) !== 0
            && $request->isMethod('GET')
            && ! $request->is('up')
        ) {
            return redirect()->away(rtrim((string) config('app.url'), '/') . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
