<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\BlockedDateController;
use App\Http\Controllers\VisitScheduleController;
use App\Http\Controllers\ReceiptController;

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

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('destroy');
});

// Booking routes 
Route::middleware(['auth'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
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
});

use App\Http\Controllers\Admin\PackageController;

// Admin schedule routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/schedule', [BlockedDateController::class, 'index'])->name('schedule');
    Route::post('/block-date', [BlockedDateController::class, 'store'])->name('block.date');
    Route::delete('/block-date/{id}', [BlockedDateController::class, 'destroy'])->name('unblock.date');
    Route::post('/booking/{id}/approve', [BlockedDateController::class, 'approveBooking'])->name('booking.approve');
    Route::post('/booking/{id}/reject', [BlockedDateController::class, 'rejectBooking'])->name('booking.reject');
    Route::put('/booking/{id}', [BlockedDateController::class, 'updateBooking'])->name('booking.update');
    Route::delete('/booking/{id}', [BlockedDateController::class, 'destroyBooking'])->name('booking.destroy');
    Route::post('/booking/{id}/confirm-downpayment', [BookingController::class, 'confirmDownPayment'])->name('booking.confirm-downpayment');

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
});

// Chat Client routes
Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/messages', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
Route::get('/chat/admin-status', [App\Http\Controllers\ChatController::class, 'getAdminStatus'])->name('chat.admin-status');