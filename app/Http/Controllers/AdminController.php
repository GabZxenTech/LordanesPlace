<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Password;

class AdminController extends Controller
{
    // Show all users
    public function dashboard()
    {
        // An unverified signup isn't a real account yet — it's just an
        // in-progress registration waiting on OTP confirmation (which may
        // never complete, e.g. if the email never arrived). Excluding these
        // keeps the admin's user list to actual, confirmed accounts.
        $users = User::where('role', '!=', 'admin')
            ->whereNotNull('email_verified_at')
            ->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('users'));
    }

    // Edit user form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Full Name and Email Address are permanently locked from this form —
     * display-only, per policy. This method intentionally never reads or
     * assigns $request->name / $request->email / pending_email, so even a
     * manually crafted PUT request (bypassing the disabled UI fields) cannot
     * change them through this endpoint. The route is kept (rather than
     * removed) specifically so that guarantee is enforced server-side, not
     * just implied by the field being absent from the form. The only way to
     * change a user's password is sendPasswordReset() below.
     */
    public function update($id)
    {
        User::findOrFail($id);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Full Name and Email Address cannot be changed from this page. Use "Send Password Reset Link" to let the user reset their own password.');
    }

    /**
     * Trigger Laravel's password-reset broker for a user instead of letting
     * the admin set a password directly — the admin never sees or chooses it.
     */
    public function sendPasswordReset($id)
    {
        $user = User::findOrFail($id);

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', "Password reset link sent to {$user->email}.");
        }

        return back()->withErrors(['error' => 'Could not send the password reset link. Please try again.']);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}