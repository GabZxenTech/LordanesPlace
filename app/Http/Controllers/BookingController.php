<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BlockedDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Show booking form with calendar
    public function index()
    {
        $packages = \App\Models\Package::all();
        
        // Group booked dates by package
        $bookings = Booking::where('status', '!=', 'rejected')->get();
        $bookedDatesByPackage = [];
        foreach ($bookings as $booking) {
            $bookedDatesByPackage[$booking->package][] = $booking->event_date->format('Y-m-d');
        }

        // Get all blocked dates (apply globally)
        $blockedDates = BlockedDate::pluck('date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        return view('booking', compact('packages', 'bookedDatesByPackage', 'blockedDates'));
    }

    // Store new booking
    public function store(Request $request)
    {
        $request->validate([
            'event_type'  => 'required|string|max:255',
            'package'     => 'required|string',
            'event_date'  => 'required|date|after:today',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'guest_count' => 'required|integer|min:1',
            'notes'       => 'nullable|string|max:1000',
        ]);

        $package = \App\Models\Package::where('name', $request->package)->first();
        
        if ($package && $request->guest_count > $package->max_guests) {
            return back()->withErrors(['guest_count' => 'Guest count exceeds the maximum allowed for the ' . $package->name . ' package (Max: ' . $package->max_guests . ').'])->withInput();
        }

        // Check if date is available for this specific package
        $isBooked = Booking::where('event_date', $request->event_date)
            ->where('package', $request->package)
            ->where('status', '!=', 'rejected')
            ->exists();

        $isBlocked = BlockedDate::where('date', $request->event_date)->exists();

        if ($isBooked || $isBlocked) {
            return back()->withErrors(['event_date' => 'Sorry, this date is already unavailable for the selected package. Please choose another date or package.'])->withInput();
        }

        Booking::create([
            'user_id'     => Auth::id(),
            'event_type'  => $request->event_type,
            'package'     => $request->package,
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'guest_count' => $request->guest_count,
            'notes'       => $request->notes,
            'status'      => 'pending',
        ]);

        return redirect()->route('booking')->with('booking_success', true);
    }

    // Booking success page
    public function success()
    {
        return view('booking-success');
    }

    // My bookings (for logged in user)
    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->orderBy('event_date', 'desc')
            ->get();

        return view('my-bookings', compact('bookings'));
    }
}