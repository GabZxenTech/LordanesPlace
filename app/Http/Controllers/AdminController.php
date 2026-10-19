<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminController extends Controller
{
    // Admin dashboard: list of users.
    public function dashboard(Request $request)
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

        return view('admin.dashboard', compact('users', 'stats', 'search'));
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

        return redirect()->route('admin.dashboard')
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

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}