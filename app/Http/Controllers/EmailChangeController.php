<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailChangeController extends Controller
{
    /**
     * Handle a signed confirmation link sent to a user's NEW email address
     * after an admin requested an email change for their account. The
     * account only ever adopts the new address here — never at request time.
     */
    public function confirm(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            return view('auth.email-change-result', [
                'success' => false,
                'title'   => 'Link Expired',
                'message' => 'This confirmation link is invalid or has expired. Please ask an administrator to resend the email change request.',
            ]);
        }

        $pendingEmail = $request->query('email');

        if (!$user->pending_email || $user->pending_email !== $pendingEmail) {
            return view('auth.email-change-result', [
                'success' => false,
                'title'   => 'Link No Longer Valid',
                'message' => 'This confirmation link is no longer valid. It may have already been used, or the pending email was changed by an administrator since this link was sent.',
            ]);
        }

        // Guard against a race where the address was claimed by another
        // account between the request being sent and this link being clicked.
        if (User::where('id', '!=', $user->id)->where('email', $pendingEmail)->exists()) {
            $user->pending_email = null;
            $user->save();

            return view('auth.email-change-result', [
                'success' => false,
                'title'   => 'Email No Longer Available',
                'message' => 'That email address is now in use by another account. Please ask an administrator to try a different address.',
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
