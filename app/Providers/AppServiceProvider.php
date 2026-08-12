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
