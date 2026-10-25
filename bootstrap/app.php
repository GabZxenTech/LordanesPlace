<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        // Note: this only fires if something actually runs `php artisan
        // schedule:run` every minute in production (a Render Cron Job, an
        // external pinger, etc.) — no such trigger exists yet, so the same
        // rules are also applied opportunistically from BookingController@profile
        // and Admin\BlockedDateController@index so they take effect today.
        $schedule->command('bookings:auto-cancel')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->prepend(\App\Http\Middleware\RedirectToCanonicalHost::class);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->web(append: [\App\Http\Middleware\PreventBackHistory::class]);
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // An expired form (old tab or old address) goes back with a short message, not a 419 page.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            // 419 is how Laravel reports an expired form token.
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }

            $message = 'Your page expired. Please try again.';

            if ($request->is('login', 'register')) {
                return redirect('/' . $request->path())->withErrors(['email' => $message]);
            }

            return redirect()->back(fallback: '/')->withInput()->withErrors(['session' => $message]);
        });
    })->create();
