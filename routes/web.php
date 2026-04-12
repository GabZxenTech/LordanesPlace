<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\BlockedDateController;

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
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my.bookings');
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

    // Packages CRUD
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');
});