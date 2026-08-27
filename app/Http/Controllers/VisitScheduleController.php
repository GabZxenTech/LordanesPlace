<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\VisitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitScheduleController extends Controller
{
    // Show follow-up page to schedule visit
    public function create(Request $request)
    {
        $booking = Booking::findOrFail($request->booking);
        
        // Ensure the user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('visit-schedule', compact('booking'));
    }

    // Store visit schedule
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time' => 'required',
            'notes'      => 'nullable|string|max:1000',
        ], [
            'visit_date.required'       => 'Please select a visit schedule.',
            'visit_date.after_or_equal' => 'The Site Visit date cannot be in the past.',
            'visit_time.required'       => 'Please select a visit schedule.',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $visitDateOnly = \Carbon\Carbon::parse($request->visit_date)->startOfDay();
        $eventDateOnly = \Carbon\Carbon::parse($booking->event_date)->startOfDay();

        // Validation: Visit date must be strictly BEFORE event date (cannot be same day or after)
        if ($visitDateOnly->greaterThanOrEqualTo($eventDateOnly)) {
            return back()->withErrors(['visit_date' => 'The Site Visit must be scheduled before your event date.'])->withInput();
        }

        $visitDateTime = \Carbon\Carbon::parse($request->visit_date . ' ' . $request->visit_time);

        VisitSchedule::create([
            'booking_id' => $request->booking_id,
            'user_id'    => Auth::id(),
            'visit_date' => $visitDateTime,
            'notes'      => $request->notes,
            'status'     => 'pending',
        ]);

        return redirect()->route('booking')->with('visit_success', true);
    }

    // Client view of their own schedules
    public function index()
    {
        $visits = VisitSchedule::where('user_id', Auth::id())
            ->with('booking')
            ->orderBy('visit_date', 'asc')
            ->get();
        return view('my-visits', compact('visits'));
    }

    // Admin view of all schedules
    public function adminIndex()
    {
        $visits = VisitSchedule::with(['user', 'booking'])->orderBy('visit_date', 'asc')->get();
        return view('admin.visits', compact('visits'));
    }

    // Admin confirm visit
    public function confirm($id)
    {
        VisitSchedule::findOrFail($id)->update(['status' => 'confirmed']);
        return back()->with('success', 'Visit confirmed.');
    }

    // Admin reschedule visit
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
        ], [
            'visit_date.after_or_equal' => 'The Site Visit date cannot be in the past.',
        ]);

        $visit = VisitSchedule::findOrFail($id);
        $visitDateOnly = \Carbon\Carbon::parse($request->visit_date)->startOfDay();
        $eventDateOnly = \Carbon\Carbon::parse($visit->booking->event_date)->startOfDay();
        
        // Validation: Visit date must be strictly BEFORE event date
        if ($visitDateOnly->greaterThanOrEqualTo($eventDateOnly)) {
            return back()->withErrors(['visit_date' => 'The Site Visit must be scheduled before your event date.']);
        }

        $visit->update([
            'visit_date' => $request->visit_date,
            'status'     => 'rescheduled',
        ]);

        return back()->with('success', 'Visit rescheduled.');
    }

    // Admin mark completed
    public function complete($id)
    {
        VisitSchedule::findOrFail($id)->update(['status' => 'completed']);
        return back()->with('success', 'Visit marked as completed.');
    }
}
