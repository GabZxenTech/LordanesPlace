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
            'total_amount' => 'required|numeric|min:0',
        ]);

        $booking->update([
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'guest_count' => $request->guest_count,
            'notes'       => $request->notes,
            'package'     => $request->package,
            'total_amount' => $request->total_amount,
            'down_payment_amount' => $request->total_amount * 0.20,
        ]);

        return back()->with('success', 'Booking updated successfully.');
    }

    // Delete booking
    public function destroyBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }

    // Show all pending reschedule requests
    public function rescheduleRequests()
    {
        $reschedules = Booking::with('user', 'visitSchedules')
            ->where('reschedule_status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.reschedules', compact('reschedules'));
    }

    // Approve a reschedule request
    public function approveReschedule($id)
    {
        $booking = Booking::with('visitSchedules')->findOrFail($id);

        if ($booking->reschedule_status !== 'pending') {
            return back()->withErrors(['error' => 'This reschedule request is not pending.']);
        }

        // Update the event date
        $booking->event_date = $booking->requested_event_date;

        // Update or create visit schedule if requested
        if ($booking->requested_visit_date) {
            $visit = $booking->visitSchedules->first();
            if ($visit) {
                $visit->update([
                    'visit_date' => $booking->requested_visit_date,
                    'status'     => 'rescheduled',
                ]);
            }
        }

        // Finalize reschedule
        $booking->reschedule_count += 1;
        $booking->reschedule_status = 'approved';
        $booking->requested_event_date = null;
        $booking->requested_visit_date = null;
        $booking->reschedule_reason = null;
        $booking->save();

        return back()->with('success', 'Reschedule approved. Booking dates updated.');
    }

    // Reject a reschedule request
    public function rejectReschedule($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->reschedule_status !== 'pending') {
            return back()->withErrors(['error' => 'This reschedule request is not pending.']);
        }

        $booking->update([
            'reschedule_status'    => 'rejected',
            'requested_event_date' => null,
            'requested_visit_date' => null,
            'reschedule_reason'    => null,
        ]);

        return back()->with('success', 'Reschedule request rejected.');
    }
}