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
