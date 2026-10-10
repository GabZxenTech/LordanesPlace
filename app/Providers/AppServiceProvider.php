<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pin every absolute URL (route(), url(), signed links used by password
        // reset and the admin email-change confirmation) to APP_URL instead of
        // the current request's Host header. Without this, a link generated
        // while an admin is browsing the panel via http://localhost/... gets
        // "localhost" baked into it — which is meaningless to the customer's
        // device receiving that link in an email.
        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));

        // Laravel has no built-in Mailjet driver; wire Symfony's API transport
        // so MAIL_MAILER=mailjet sends over HTTPS rather than blocked SMTP.
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

        // Brevo over its HTTPS API, selected with MAIL_MAILER=brevo.
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

        // Dropdowns intentionally load ONLY unread notifications (latest 5) so the
        // bell never accumulates already-read history. Full history lives on the
        // dedicated "View All Notifications" page.
        View::composer('partials._navbar', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (Auth::check()) {
                $unread = Notification::forRecipient('customer', Auth::id())->unread();
                $unreadCount = (clone $unread)->count();
                $notifications = $unread->latest()->limit(5)->get();
            }

            $view->with('navNotifications', $notifications);
            $view->with('navUnreadCount', $unreadCount);
        });

        View::composer('partials._admin-sidebar', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (Auth::check() && Auth::user()->role === 'admin') {
                $unread = Notification::forRecipient('admin', Auth::id())->unread();
                $unreadCount = (clone $unread)->count();
                $notifications = $unread->latest()->limit(5)->get();
            }

            $view->with('adminNotifications', $notifications);
            $view->with('adminUnreadCount', $unreadCount);
        });
    }
}
