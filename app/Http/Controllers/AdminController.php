<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Payment;
use App\Models\User;
use App\Models\VisitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminController extends Controller
{
    // Admin dashboard: overview of bookings, revenue and pending work.
    public function dashboard()
    {
        $today = now()->startOfDay();
        $open = [Booking::STATUS_PENDING, Booking::STATUS_APPROVED, Booking::STATUS_ONGOING];

        $stats = [
            'pending'  => Booking::where('status', Booking::STATUS_PENDING)->count(),
            'upcoming' => Booking::whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_ONGOING])
                ->whereBetween('event_date', [$today, now()->addDays(30)->endOfDay()])->count(),
            'revenue'  => (float) Payment::where('created_at', '>=', now()->startOfMonth())->sum('amount'),
            'balance'  => Booking::with('payments')->whereIn('status', $open)->get()
                ->sum(fn ($b) => max(0, $b->remainingBalance())),
        ];

        // Last 6 months, oldest first.
        $months = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
        $bookingsByMonth = Booking::where('created_at', '>=', $months->first())->get(['created_at'])
            ->countBy(fn ($b) => $b->created_at->format('Y-m'));
        $revenueByMonth = Payment::where('created_at', '>=', $months->first())->get(['amount', 'created_at'])
            ->groupBy(fn ($p) => $p->created_at->format('Y-m'))
            ->map(fn ($g) => (float) $g->sum('amount'));
        $monthlyChart = [
            'labels'   => $months->map(fn ($m) => $m->format('M Y'))->all(),
            'bookings' => $months->map(fn ($m) => (int) ($bookingsByMonth[$m->format('Y-m')] ?? 0))->all(),
            'revenue'  => $months->map(fn ($m) => (float) ($revenueByMonth[$m->format('Y-m')] ?? 0))->all(),
        ];

        $statusCounts = Booking::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $statusChart = collect(Booking::STATUSES)->map(fn ($s) => (int) ($statusCounts[$s] ?? 0))->all();

        $topPackages = Booking::selectRaw('package, COUNT(*) as total')
            ->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_REJECTED])
            ->groupBy('package')->orderByDesc('total')->limit(5)->get();

        $upcomingEvents = Booking::with('user')
            ->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_ONGOING])
            ->where('event_date', '>=', $today)->orderBy('event_date')->limit(6)->get();

        $pendingBookings = Booking::with('user')->where('status', Booking::STATUS_PENDING)
            ->orderBy('event_date')->limit(6)->get();

        $recentBookings = Booking::with('user')->latest()->limit(6)->get();

        $pendingReschedules = Booking::where('reschedule_status', 'pending')->count();
        $pendingVisits = VisitSchedule::where('status', VisitSchedule::STATUS_PENDING)->count();
        $unreadChats = Conversation::unreadCountForAdmin();

        return view('admin.dashboard', compact(
            'stats', 'monthlyChart', 'statusChart', 'topPackages', 'upcomingEvents',
            'pendingBookings', 'recentBookings', 'pendingReschedules', 'pendingVisits', 'unreadChats'
        ));
    }

    // User management: list of users.
    public function users(Request $request)
    {
        // Unverified signups are not shown.
        $base = User::where('role', '!=', 'admin')->whereNotNull('email_verified_at');

        $stats = [
            'total'  => (clone $base)->count(),
            'active' => (clone $base)->where('last_active', '>=', now()->subMinutes(30))->count(),
            'today'  => (clone $base)->where('created_at', '>=', now()->startOfDay())->count(),
        ];

        // Search by name or email (case-insensitive), 20 users per page.
        $search = trim((string) $request->query('q', ''));
        $users = (clone $base)
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . mb_strtolower($search) . '%';
                $q->where(fn ($w) => $w->whereRaw('LOWER(name) LIKE ?', [$like])->orWhereRaw('LOWER(email) LIKE ?', [$like]));
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', compact('users', 'stats', 'search'));
    }

    // Edit user form.
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    // Updates a user. Name and email can't be changed here, even by a crafted request.
    public function update($id)
    {
        User::findOrFail($id);

        return redirect()->route('admin.users')
            ->with('success', 'Full Name and Email Address cannot be changed from this page. Use "Send Password Reset Link" to let the user reset their own password.');
    }

    // Sends the user a password-reset email (the admin never sets the password).
    public function sendPasswordReset($id)
    {
        $user = User::findOrFail($id);

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', "Password reset link sent to {$user->email}.");
        }

        return back()->withErrors(['error' => 'Could not send the password reset link. Please try again.']);
    }

    // Delete a user.
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}