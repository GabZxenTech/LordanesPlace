<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Rate limit for account settings actions, counted separately per action.
        \Illuminate\Support\Facades\RateLimiter::for('account', function ($request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)
                ->by($request->user()?->id . '|' . $request->route()?->getName());
        });

        // All generated links use APP_URL (so emailed links never say "localhost").
        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));

        // Mailjet email driver (MAIL_MAILER=mailjet).
        \Illuminate\Support\Facades\Mail::extend('mailjet', function () {
            return (new \Symfony\Component\Mailer\Bridge\Mailjet\Transport\MailjetTransportFactory())->create(
                new \Symfony\Component\Mailer\Transport\Dsn(
                    'mailjet+api',
                    'default',
                    config('services.mailjet.key'),
                    config('services.mailjet.secret')
                )
            );
        });

        // Brevo email driver (MAIL_MAILER=brevo).
        \Illuminate\Support\Facades\Mail::extend('brevo', function () {
            return (new \Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory())->create(
                new \Symfony\Component\Mailer\Transport\Dsn(
                    'brevo+api',
                    'default',
                    config('services.brevo.key')
                )
            );
        });

        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Latest notifications for the bell dropdown and the unread count.
        View::composer('partials._navbar', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (Auth::check()) {
                $unreadCount = Notification::forRecipient('customer', Auth::id())->unread()->count();
                $notifications = Notification::forRecipient('customer', Auth::id())
                    ->latest()->limit(Notification::DROPDOWN_LIMIT)->get();
            }

            $view->with('navNotifications', $notifications);
            $view->with('navUnreadCount', $unreadCount);
        });

        View::composer('partials._admin-sidebar', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (Auth::check() && Auth::user()->role === 'admin') {
                $unreadCount = Notification::forRecipient('admin', Auth::id())->unread()->count();
                $notifications = Notification::forRecipient('admin', Auth::id())
                    ->latest()->limit(Notification::DROPDOWN_LIMIT)->get();
            }

            $view->with('adminNotifications', $notifications);
            $view->with('adminUnreadCount', $unreadCount);
            // Chat badge count.
            $view->with('adminUnreadChats', Auth::check() && Auth::user()->role === 'admin' ? \App\Models\Conversation::unreadCountForAdmin() : 0);
        });
    }
}
