<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangeConfirmationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Show all users
    public function dashboard()
    {
        $users = User::where('role', '!=', 'admin')->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('users'));
    }

    // Edit user form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update a user's non-sensitive details. Email is never overwritten
     * immediately here — a change is only staged (pending_email) and applied
     * once the new address owner confirms it via emailed link, so an admin
     * (or anyone with admin access) can't silently redirect an account to a
     * mailbox they control. Passwords are no longer editable from this form
     * at all — see sendPasswordReset().
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
                function ($attribute, $value, $fail) use ($id) {
                    if (User::where('id', '!=', $id)->where('pending_email', $value)->exists()) {
                        $fail('This email is already pending confirmation on another account.');
                    }
                },
            ],
        ]);

        $user->name = $request->name;

        $newEmail = $request->email;

        if ($newEmail === $user->email) {
            // Typing the current email back in cancels any stale pending change.
            $user->pending_email = null;
            $user->save();

            return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
        }

        $user->pending_email = $newEmail;
        $user->save();

        $confirmUrl = URL::temporarySignedRoute(
            'email-change.confirm',
            now()->addHours(24),
            ['user' => $user->id, 'email' => $newEmail]
        );

        try {
            Mail::to($newEmail)->send(new EmailChangeConfirmationMail($confirmUrl, $user->name, $user->email, $newEmail));
            $status = "Confirmation link sent to {$newEmail}. The email will update once the link is confirmed.";
        } catch (\Exception $e) {
            Log::error('Email change confirmation mail failed: ' . $e->getMessage());
            $status = "User updated, but the confirmation email to {$newEmail} could not be sent. Please try again.";
        }

        return redirect()->route('admin.dashboard')->with('success', $status);
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