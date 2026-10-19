<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangeConfirmationMail;
use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Models\VisitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    // Bookings in these statuses stop the account from being deleted.
    private const ACTIVE_STATUSES = [Booking::STATUS_PENDING, Booking::STATUS_APPROVED, Booking::STATUS_ONGOING];

    // Account settings page.
    public function show()
    {
        $user = Auth::user();
        $completedCount = Booking::where('user_id', $user->id)->where('status', Booking::STATUS_COMPLETED)->count();
        $activeBookings = Booking::where('user_id', $user->id)->whereIn('status', self::ACTIVE_STATUSES)->count();

        return view('account.settings', compact('user', 'completedCount', 'activeBookings'));
    }

    // Saves the name; a new email is only applied after the link sent to it is clicked.
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $emailChanged = mb_strtolower(trim($request->input('email', ''))) !== mb_strtolower($user->email);

        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => ['required', 'email', 'max:255', $emailChanged ? 'unique:users,email' : 'nullable'],
            'email_password'   => $emailChanged ? 'required|string' : 'nullable',
        ], [
            'email.unique'              => 'That email address is already used by another account.',
            'email_password.required'   => 'Enter your current password to change your email.',
        ]);

        $user->name = $request->name;

        if ($emailChanged) {
            if (!Hash::check($request->email_password, $user->password)) {
                return back()->withErrors(['email_password' => 'Your current password is incorrect.'])->withInput();
            }

            $newEmail = mb_strtolower(trim($request->email));
            $user->pending_email = $newEmail;
            $user->save();

            $url = URL::temporarySignedRoute('email-change.confirm', now()->addHours(24), ['user' => $user->id, 'email' => $newEmail]);

            try {
                Mail::to($newEmail)->send(new EmailChangeConfirmationMail($url, $user->name, $user->email, $newEmail));
            } catch (\Throwable $e) {
                Log::error('Email change mail failed: ' . $e->getMessage());
                return back()->withErrors(['email' => 'We could not send the confirmation email. Please try again later.'])->withInput();
            }

            return back()->with('success', "Profile saved. We sent a confirmation link to {$newEmail}. Your email changes once you click it.");
        }

        $user->save();

        return back()->with('success', 'Profile updated.');
    }

    // Saves a new profile picture (already cropped to a small square in the browser).
    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar_data' => 'required|string|max:700000']);

        if (!preg_match('#^data:image/(jpeg|png|webp);base64,([A-Za-z0-9+/=]+)$#', $request->avatar_data, $m)) {
            return back()->withErrors(['avatar' => 'Please choose a JPG, PNG or WebP image.']);
        }

        $bytes = base64_decode($m[2], true);
        $info = $bytes ? @getimagesizefromstring($bytes) : false;

        if (!$info || !in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'], true)
            || $info[0] > 1024 || $info[1] > 1024 || strlen($bytes) > 500000) {
            return back()->withErrors(['avatar' => 'That image could not be used. Please choose another photo.']);
        }

        Auth::user()->forceFill(['avatar_image' => $request->avatar_data])->save();

        return back()->with('success', 'Profile picture updated.');
    }

    // Removes the uploaded profile picture (and the Google photo).
    public function removeAvatar()
    {
        Auth::user()->forceFill(['avatar_image' => null, 'avatar' => null])->save();

        return back()->with('success', 'Profile picture removed.');
    }

    // Serves a user's uploaded profile picture.
    public function avatar(User $user)
    {
        $me = Auth::user();
        abort_unless($me->id === $user->id || $me->role === 'admin', 403);
        abort_unless($user->avatar_image && preg_match('#^data:(image/(?:jpeg|png|webp));base64,(.+)$#s', $user->avatar_image, $m), 404);

        return response(base64_decode($m[2]), 200, [
            'Content-Type'  => $m[1],
            'Cache-Control' => 'private, max-age=604800',
        ]);
    }

    // Changes the password (same rules as sign up).
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password'         => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                'not_in:' . implode(',', AuthController::commonPasswords()),
            ],
        ], [
            'password.confirmed' => 'The new passwords do not match.',
            'password.different' => 'The new password must be different from the current one.',
            'password.not_in'    => 'This password is too common. Please choose a stronger password.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated.');
    }

    // Deletes the account: personal data is removed, paid bookings stay for the records.
    public function destroy(Request $request)
    {
        $user = Auth::user();

        $request->validate(['delete_password' => 'required|string'], [
            'delete_password.required' => 'Enter your password to delete your account.',
        ]);

        if (!Hash::check($request->delete_password, $user->password)) {
            return back()->withErrors(['delete_password' => 'Your password is incorrect. The account was not deleted.']);
        }

        if (Booking::where('user_id', $user->id)->whereIn('status', self::ACTIVE_STATUSES)->exists()) {
            return back()->withErrors(['delete_password' => 'You still have active bookings. Cancel them or wait until they are finished.']);
        }

        DB::transaction(function () use ($user) {
            // Unpaid bookings are removed; paid ones stay for reports.
            $unpaidIds = Booking::where('user_id', $user->id)->whereDoesntHave('payments')->pluck('id');
            VisitSchedule::withTrashed()->whereIn('booking_id', $unpaidIds)->forceDelete();
            Notification::whereIn('booking_id', $unpaidIds)->delete();
            Booking::whereIn('id', $unpaidIds)->delete();

            Notification::where('recipient_type', 'customer')->where('recipient_id', $user->id)->delete();
            $conversationIds = Conversation::where('user_id', $user->id)->pluck('id');
            Message::whereIn('conversation_id', $conversationIds)->delete();
            Conversation::whereIn('id', $conversationIds)->delete();
            DB::table('sessions')->where('user_id', $user->id)->delete();

            $user->forceFill([
                'name'              => 'Deleted user',
                'email'             => 'deleted-' . $user->id . '-' . Str::lower(Str::random(8)) . '@deleted.invalid',
                'pending_email'     => null,
                'password'          => Hash::make(Str::random(40)),
                'google_id'         => null,
                'avatar'            => null,
                'avatar_image'      => null,
                'remember_token'    => null,
                'email_verified_at' => null,
                'is_online'         => false,
                'deactivated_at'    => now(),
            ])->save();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Your account has been deleted.');
    }
}
