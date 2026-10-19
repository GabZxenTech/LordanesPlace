<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailChangeController extends Controller
{
    // Confirms an email change from the link sent to the new address.
    public function confirm(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            return view('auth.email-change-result', [
                'success' => false,
                'title'   => 'Link Expired',
                'message' => 'This confirmation link is invalid or has expired. Please request the change again from Account Settings.',
            ]);
        }

        $pendingEmail = $request->query('email');

        if (!$user->pending_email || $user->pending_email !== $pendingEmail) {
            return view('auth.email-change-result', [
                'success' => false,
                'title'   => 'Link No Longer Valid',
                'message' => 'This confirmation link is no longer valid. It may have already been used, or a newer change was requested since this link was sent.',
            ]);
        }

        // Stop if another account took this email in the meantime.
        if (User::where('id', '!=', $user->id)->where('email', $pendingEmail)->exists()) {
            $user->pending_email = null;
            $user->save();

            return view('auth.email-change-result', [
                'success' => false,
                'title'   => 'Email No Longer Available',
                'message' => 'That email address is now in use by another account. Please request the change again with a different address.',
            ]);
        }

        $user->email = $pendingEmail;
        $user->pending_email = null;
        $user->email_verified_at = now();
        $user->save();

        return view('auth.email-change-result', [
            'success' => true,
            'title'   => 'Email Confirmed',
            'message' => "This account's email address has been updated to {$user->email}.",
        ]);
    }
}
