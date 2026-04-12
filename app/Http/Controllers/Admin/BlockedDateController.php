<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Booking;
use Illuminate\Http\Request;

class BlockedDateController extends Controller
{
    // Show all bookings + blocked dates in admin
    public function index()
    {
        $bookings = Booking::with('user')->orderBy('event_date', 'asc')->get();
        $blockedDates = BlockedDate::orderBy('date', 'asc')->get();

        return view('admin.schedule', compact('bookings', 'blockedDates'));
    }

    // Block a date
    public function store(Request $request)
    {
        $request->validate([
            'date'   => 'required|date|unique:blocked_dates,date',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedDate::create([
            'date'   => $request->date,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Date blocked successfully.');
    }

    // Unblock a date
    public function destroy($id)
    {
        BlockedDate::findOrFail($id)->delete();
        return back()->with('success', 'Date unblocked successfully.');
    }

    // Approve booking
    public function approveBooking($id)
    {
        Booking::findOrFail($id)->update(['status' => 'approved']);
        return back()->with('success', 'Booking approved.');
    }

    // Reject booking
    public function rejectBooking($id)
    {
        Booking::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Booking rejected.');
    }

    // Update booking details
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'guest_count' => 'required|integer|min:1',
            'notes'       => 'nullable|string',
            'package'     => 'required|string',
        ]);

        $booking->update([
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'guest_count' => $request->guest_count,
            'notes'       => $request->notes,
            'package'     => $request->package,
        ]);

        return back()->with('success', 'Booking updated successfully.');
    }

    // Delete booking
    public function destroyBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }
}