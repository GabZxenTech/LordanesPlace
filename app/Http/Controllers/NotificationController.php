<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Base query scoped to the logged-in customer. Every action funnels through
     * this so a user can never touch another user's notifications.
     */
    protected function scoped()
    {
        return Notification::forRecipient('customer', Auth::id());
    }

    // Full notification history with All / Unread / Read filtering
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        if (!in_array($filter, ['all', 'unread', 'read'], true)) {
            $filter = 'all';
        }

        $query = $this->scoped();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = $this->scoped()->unread()->count();

        return view('notifications.index', compact('notifications', 'filter', 'unreadCount'));
    }

    // Bell polling: unread count + the latest 5 unread, rendered with the same
    // partial the navbar uses on page load (so it's escaped and identical).
    public function poll()
    {
        $unread = $this->scoped()->unread();
        $unreadCount = (clone $unread)->count();
        $notifications = $unread->latest()->limit(5)->get();

        return response()->json([
            'unread_count' => $unreadCount,
            'ids' => $notifications->pluck('id'),
            'html' => $notifications
                ->map(fn ($notif) => view('partials._nav-notif-item', compact('notif'))->render())
                ->implode(''),
        ]);
    }

    // Mark a single notification as read (AJAX-aware so the bell can update live)
    public function markRead(Request $request, $id)
    {
        $notification = $this->scoped()->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $this->scoped()->unread()->count(),
            ]);
        }

        return back();
    }

    // Mark every unread notification as read
    public function markAllRead(Request $request)
    {
        $this->scoped()->unread()->update(['is_read' => true]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'unread_count' => 0]);
        }

        return back();
    }

    // Delete a single notification
    public function destroy(Request $request, $id)
    {
        $notification = $this->scoped()->findOrFail($id);
        $notification->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $this->scoped()->unread()->count(),
            ]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    // Mark as read, then jump to the related booking
    public function open($id)
    {
        $notification = $this->scoped()->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        if ($notification->booking_id) {
            return redirect(route('profile') . '#booking-' . $notification->booking_id);
        }

        return redirect()->route('profile');
    }
}
