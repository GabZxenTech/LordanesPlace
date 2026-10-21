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
use App\Http\Controllers\ForgotPasswordController;

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

Route::get('/faq', function () {
    return view('faq', ['faqs' => \App\Models\Faq::published()]);
})->name('faq');

// Forgot password (email code).
Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendCode'])->name('password.otp.send');
Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.otp.verify.show');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyCode'])->name('password.otp.verify');
Route::post('/forgot-password/resend', [ForgotPasswordController::class, 'resendCode'])->name('password.otp.resend');
Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.otp.reset.show');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'submitReset'])->name('password.otp.reset');

// Password reset link sent by an admin.
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Email change confirmation link (works without login, protected by signature).
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

// Email verification code (logged in, not yet verified).
Route::middleware(['auth'])->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])->name('otp.resend');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('destroy');
    Route::post('/users/{id}/send-password-reset', [AdminController::class, 'sendPasswordReset'])->name('users.send-password-reset');
});

// Customer pages (verified email required).
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/check-date', [BookingController::class, 'checkDate'])->name('booking.check-date');
    Route::get('/booking/check-room-availability', [BookingController::class, 'checkRoomAvailability'])->name('booking.check-room-availability');
    Route::get('/booking/success', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/profile', [BookingController::class, 'profile'])->name('profile');
    Route::get('/terms-and-conditions', function() { return view('terms'); })->name('terms');

    // Account settings.
    Route::get('/account/settings', [App\Http\Controllers\AccountController::class, 'show'])->name('account.settings');
    Route::post('/account/profile', [App\Http\Controllers\AccountController::class, 'updateProfile'])->middleware('throttle:account')->name('account.profile');
    Route::post('/account/avatar', [App\Http\Controllers\AccountController::class, 'updateAvatar'])->name('account.avatar.update');
    Route::post('/account/avatar/remove', [App\Http\Controllers\AccountController::class, 'removeAvatar'])->name('account.avatar.remove');
    Route::get('/account/avatar/{user}', [App\Http\Controllers\AccountController::class, 'avatar'])->name('account.avatar');
    Route::post('/account/password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->middleware('throttle:account')->name('account.password');
    Route::post('/account/delete', [App\Http\Controllers\AccountController::class, 'destroy'])->middleware('throttle:account')->name('account.delete');

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

    // Customer hides a finished booking.
    Route::post('/booking/{id}/delete', [BookingController::class, 'deleteForCustomer'])->name('booking.delete');

    // Notifications (client)
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/poll', [\App\Http\Controllers\NotificationController::class, 'poll'])->name('notifications.poll');
    Route::get('/notifications/{id}/open', [\App\Http\Controllers\NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ReportController;

// Admin pages.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
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
    Route::get('/notifications/poll', [\App\Http\Controllers\Admin\NotificationController::class, 'poll'])->name('notifications.poll');
    Route::get('/notifications/{id}/open', [\App\Http\Controllers\Admin\NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Admin Visit Schedule routes
    Route::get('/visits', [VisitScheduleController::class, 'adminIndex'])->name('visits.index');
    Route::post('/visits/{id}/confirm', [VisitScheduleController::class, 'confirm'])->name('visits.confirm');
    Route::post('/visits/{id}/reschedule', [VisitScheduleController::class, 'reschedule'])->name('visits.reschedule');
    Route::post('/visits/{id}/complete', [VisitScheduleController::class, 'complete'])->name('visits.complete');
    Route::delete('/visits/{id}', [VisitScheduleController::class, 'destroy'])->name('visits.destroy');

    // Packages CRUD
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');

    // Reschedule management (admin)
    Route::get('/reschedules', [BlockedDateController::class, 'rescheduleRequests'])->name('reschedules.index');
    Route::post('/reschedule/{id}/approve', [BlockedDateController::class, 'approveReschedule'])->name('reschedule.approve');
    Route::post('/reschedule/{id}/reject', [BlockedDateController::class, 'rejectReschedule'])->name('reschedule.reject');

    // FAQs.
    Route::get('/faqs', [\App\Http\Controllers\Admin\FaqController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [\App\Http\Controllers\Admin\FaqController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/{faq}', [\App\Http\Controllers\Admin\FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('faqs.destroy');

    // Admin chat.
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'adminConversations'])->name('chat.index');
    Route::get('/chat/json', [App\Http\Controllers\ChatController::class, 'adminConversationsJson'])->name('chat.json');
    Route::get('/chat/{id}', [App\Http\Controllers\ChatController::class, 'adminOpenChat'])->name('chat.open');
    Route::post('/chat/reply', [App\Http\Controllers\ChatController::class, 'adminReply'])->name('chat.reply');
    Route::post('/chat/toggle-status', [App\Http\Controllers\ChatController::class, 'toggleStatus'])->name('chat.toggle-status');
    Route::post('/chat/delete', [App\Http\Controllers\ChatController::class, 'adminDeleteConversations'])->name('chat.delete');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
});

// Customer/guest chat (sending is rate-limited).
Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'send'])->middleware('throttle:20,1')->name('chat.send');
Route::get('/chat/messages', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
Route::get('/chat/unread', [App\Http\Controllers\ChatController::class, 'unread'])->name('chat.unread');
Route::get('/chat/admin-status', [App\Http\Controllers\ChatController::class, 'getAdminStatus'])->name('chat.admin-status');
