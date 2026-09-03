<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\BlockedDateController;
use App\Http\Controllers\VisitScheduleController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\EmailChangeController;

Route::get('/', function () {
    return view('homepage');
})->name('home');

Route::get('/tour', function () {
    return view('tour');
})->name('tour');


Route::get('/discover', function () {
    return view('discover');
})->name('discover');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/forgot-password', function () {
    return view('login');
})->name('password.request');

// Password reset (links are only ever sent by an admin from the user
// management panel — there is no public "forgot password" form).
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Confirmation link for an admin-initiated email change, sent to the NEW
// address. Must stay outside 'auth' — the person clicking it is the target
// user, not necessarily logged in. Integrity is enforced by the signature.
Route::get('/confirm-email-change/{user}', [EmailChangeController::class, 'confirm'])->name('email-change.confirm');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Auth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// OTP Email Verification (authenticated but possibly unverified)
Route::middleware(['auth'])->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('otp.resend');
});



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('destroy');
    Route::post('/users/{id}/send-password-reset', [AdminController::class, 'sendPasswordReset'])->name('users.send-password-reset');
});

// Booking routes (require verified email)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/check-date', [BookingController::class, 'checkDate'])->name('booking.check-date');
    Route::get('/booking/success', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/profile', [BookingController::class, 'profile'])->name('profile');
    Route::get('/terms-and-conditions', function() { return view('terms'); })->name('terms');

    // Visit Schedule routes
    Route::get('/visit-schedule/create', [VisitScheduleController::class, 'create'])->name('visit-schedule.create');
    Route::post('/visit-schedule', [VisitScheduleController::class, 'store'])->name('visit-schedule.store');
    Route::get('/my-visits', [VisitScheduleController::class, 'index'])->name('my.visits');

    // Receipt route
    Route::get('/bookings/{booking}/receipt', [ReceiptController::class, 'download'])->name('booking.receipt');

    // Reschedule request (client)
    Route::post('/booking/{id}/reschedule', [BookingController::class, 'submitReschedule'])->name('booking.reschedule');

    // Cancel own booking (client)
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Notifications (client)
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/open', [\App\Http\Controllers\NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ReportController;

// Admin schedule routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/schedule', [BlockedDateController::class, 'index'])->name('schedule');
    Route::post('/block-date', [BlockedDateController::class, 'store'])->name('block.date');
    Route::delete('/block-date/{id}', [BlockedDateController::class, 'destroy'])->name('unblock.date');
    Route::post('/booking/{id}/approve', [BlockedDateController::class, 'approveBooking'])->name('booking.approve');
    Route::post('/booking/{id}/reject', [BlockedDateController::class, 'rejectBooking'])->name('booking.reject');
    Route::post('/booking/{id}/cancel', [BlockedDateController::class, 'cancelBooking'])->name('booking.cancel');
    Route::post('/booking/{id}/ongoing', [BlockedDateController::class, 'markOngoing'])->name('booking.ongoing');
    Route::post('/booking/{id}/completed', [BlockedDateController::class, 'markCompleted'])->name('booking.completed');
    Route::post('/booking/{id}/status', [BlockedDateController::class, 'updateStatus'])->name('booking.status');
    Route::put('/booking/{id}', [BlockedDateController::class, 'updateBooking'])->name('booking.update');
    Route::delete('/booking/{id}', [BlockedDateController::class, 'destroyBooking'])->name('booking.destroy');
    Route::post('/booking/{id}/record-payment', [BlockedDateController::class, 'recordPayment'])->name('booking.record-payment');

    // Notifications (admin)
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/open', [\App\Http\Controllers\Admin\NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Admin Visit Schedule routes
    Route::get('/visits', [VisitScheduleController::class, 'adminIndex'])->name('visits.index');
    Route::post('/visits/{id}/confirm', [VisitScheduleController::class, 'confirm'])->name('visits.confirm');
    Route::post('/visits/{id}/reschedule', [VisitScheduleController::class, 'reschedule'])->name('visits.reschedule');
    Route::post('/visits/{id}/complete', [VisitScheduleController::class, 'complete'])->name('visits.complete');

    // Packages CRUD
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');

    // Reschedule management (admin)
    Route::get('/reschedules', [BlockedDateController::class, 'rescheduleRequests'])->name('reschedules.index');
    Route::post('/reschedule/{id}/approve', [BlockedDateController::class, 'approveReschedule'])->name('reschedule.approve');
    Route::post('/reschedule/{id}/reject', [BlockedDateController::class, 'rejectReschedule'])->name('reschedule.reject');

    // Chat Admin routes
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'adminConversations'])->name('chat.index');
    Route::get('/chat/json', [App\Http\Controllers\ChatController::class, 'adminConversationsJson'])->name('chat.json');
    Route::get('/chat/{id}', [App\Http\Controllers\ChatController::class, 'adminOpenChat'])->name('chat.open');
    Route::post('/chat/reply', [App\Http\Controllers\ChatController::class, 'adminReply'])->name('chat.reply');
    Route::post('/chat/toggle-status', [App\Http\Controllers\ChatController::class, 'toggleStatus'])->name('chat.toggle-status');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
});

// Chat Client routes
Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/messages', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
Route::get('/chat/admin-status', [App\Http\Controllers\ChatController::class, 'getAdminStatus'])->name('chat.admin-status');



// TEMPORARY: live SMTP diagnostic — admin-only, sends a real test email and
// reports the actual mailer config + success/exception back in the response,
// since Render's Logs UI has been hard to read through in this debugging
// session. Remove this route once OTP delivery is confirmed working.
Route::get('/admin/mail-test', function () {
    if (!\Illuminate\Support\Facades\Auth::check() || \Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
        abort(403);
    }

    $to = \Illuminate\Support\Facades\Auth::user()->email;
    $info = [
        'mailer_in_use' => config('mail.default'),
        'smtp_host'     => config('mail.mailers.smtp.host'),
        'smtp_port'     => config('mail.mailers.smtp.port'),
        'smtp_username' => config('mail.mailers.smtp.username'),
        'from_address'  => config('mail.from.address'),
        'sending_to'    => $to,
    ];

    try {
        \Illuminate\Support\Facades\Mail::raw(
            'This is a live SMTP test from LorDane\'s Place at ' . now()->toDateTimeString() . '.',
            function ($message) use ($to) {
                $message->to($to)->subject('LorDane\'s Place — Mail Test');
            }
        );
        $info['result'] = 'SENT — no exception was thrown. Check the inbox/spam for ' . $to . '.';
    } catch (\Throwable $e) {
        $info['result'] = 'FAILED';
        $info['exception_class'] = get_class($e);
        $info['exception_message'] = $e->getMessage();
    }

    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');

Route::get('/setup-admin', function () {
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'admin@test.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]
    );
    return "Admin account created/updated! Email: admin@test.com, Pass: admin123. PLEASE DELETE THIS ROUTE NOW.";
});
