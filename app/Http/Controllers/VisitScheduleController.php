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
            'visit_date' => 'required|date|after:today',
            'visit_time' => 'required',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $visitDateTime = \Carbon\Carbon::parse($request->visit_date . ' ' . $request->visit_time);

        // Validation: Visit date must be BEFORE event date
        if ($visitDateTime->format('Y-m-d') >= $booking->event_date->format('Y-m-d')) {
            return back()->withErrors(['visit_date' => 'The visit date must be before the event date (' . $booking->event_date->format('F d, Y') . ').'])->withInput();
        }

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
            'visit_date' => 'required|date|after:today',
        ]);

        $visit = VisitSchedule::findOrFail($id);
        
        // Validation: Visit date must be BEFORE event date
        if ($request->visit_date >= $visit->booking->event_date->format('Y-m-d')) {
            return back()->withErrors(['reschedule_date' => 'The reschedule date must be before the event date.']);
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
