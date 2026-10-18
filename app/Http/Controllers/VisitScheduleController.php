<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\VisitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitScheduleController extends Controller
{
    // Page to schedule a venue visit after booking.
    public function create(Request $request)
    {
        $booking = Booking::findOrFail($request->booking);
        
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('visit-schedule', compact('booking'));
    }

    // Saves a visit schedule.
    public function store(Request $request)
    {
        // Allowed times: 8:00 AM to 5:00 PM every 30 minutes.
        $allowedTimes = [];
        for ($h = 8; $h <= 17; $h++) {
            foreach (['00', '30'] as $m) {
                if ($h === 17 && $m === '30') continue;
                $allowedTimes[] = sprintf('%02d:%s', $h, $m);
            }
        }

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            // Other bookings from the same submission share the visit.
            'booking_ids'   => 'nullable|array|max:100',
            'booking_ids.*' => 'integer',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time' => ['required', \Illuminate\Validation\Rule::in($allowedTimes)],
            'notes'      => 'nullable|string|max:1000',
        ], [
            'visit_date.required'       => 'Please select a visit schedule.',
            'visit_date.after_or_equal' => 'The Site Visit date cannot be in the past.',
            'visit_time.required'       => 'Please select a visit time.',
            'visit_time.in'             => 'Please select a valid visit time between 8:00 AM and 5:00 PM.',
        ]);

        // Only the customer's own booking.
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $visitDateOnly = \Carbon\Carbon::parse($request->visit_date)->startOfDay();
        $eventDateOnly = \Carbon\Carbon::parse($booking->event_date)->startOfDay();

        // The visit must be before the event date.
        if ($visitDateOnly->greaterThanOrEqualTo($eventDateOnly)) {
            return back()->withErrors(['visit_date' => 'The Site Visit must be scheduled before your event date.'])->withInput();
        }

        $visitDateTime = \Carbon\Carbon::parse($request->visit_date . ' ' . $request->visit_time);

        // Same ownership check for the other bookings in the submission.
        $bookingIds = Booking::whereIn('id', array_merge([$booking->id], $request->input('booking_ids', [])))
            ->where('user_id', Auth::id())
            ->whereDate('event_date', '>=', $booking->event_date)
            ->pluck('id');

        foreach ($bookingIds as $bookingId) {
            VisitSchedule::create([
                'booking_id' => $bookingId,
                'user_id'    => Auth::id(),
                'visit_date' => $visitDateTime,
                'notes'      => $request->notes,
                'status'     => 'pending',
            ]);
        }

        return redirect()->route('booking')->with('visit_success', true);
    }

    // Customer's own visits.
    public function index()
    {
        // Includes visits hidden from the admin list.
        $visits = VisitSchedule::withTrashed()
            ->where('user_id', Auth::id())
            ->with('booking')
            ->orderBy('visit_date', 'asc')
            ->get();
        return view('my-visits', compact('visits'));
    }

    // Admin: all visits.
    public function adminIndex()
    {
        // Run the automatic rules first (at most once a minute).
        \App\Services\BookingAutoCancelService::runThrottled();

        $visits = VisitSchedule::with(['user', 'booking'])->orderBy('visit_date', 'asc')->get();
        return view('admin.visits', compact('visits'));
    }

    // The visit plus the matching visits of bookings made together (shown as one row).
    private function visitGroup(VisitSchedule $visit)
    {
        $group = $visit->booking?->booking_group;

        if (!$group) {
            return collect([$visit]);
        }

        return VisitSchedule::with('booking')
            ->where('status', $visit->status)
            ->where('visit_date', $visit->visit_date)
            ->whereHas('booking', fn ($q) => $q->where('booking_group', $group))
            ->get();
    }

    // Admin confirms a visit.
    public function confirm($id)
    {
        $visit = VisitSchedule::with('booking')->findOrFail($id);

        if (!$visit->canConfirm()) {
            return back()->withErrors(['visit' => 'Only a pending visit can be confirmed (this one is ' . $visit->status . ').']);
        }

        foreach ($this->visitGroup($visit) as $v) {
            if ($v->canConfirm()) {
                $v->update(['status' => VisitSchedule::STATUS_CONFIRMED]);
            }
        }

        // One notification per group.
        \App\Services\NotificationService::visitConfirmed($visit->fresh('booking'));

        return back()->with('success', 'Visit confirmed.');
    }

    // Admin reschedules a visit.
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'visit_date' => 'required|date|after:now',
        ], [
            'visit_date.after' => 'The new visit date and time must be in the future.',
        ]);

        $visit = VisitSchedule::with('booking')->findOrFail($id);

        if (!$visit->canReschedule()) {
            return back()->withErrors(['visit' => 'This visit can no longer be rescheduled.']);
        }

        $visits = $this->visitGroup($visit)->filter->canReschedule();

        // The visit must be before the earliest event day.
        $visitDateOnly = \Carbon\Carbon::parse($request->visit_date)->startOfDay();
        $eventDateOnly = $visits->map(fn ($v) => $v->booking->event_date)->min()->copy()->startOfDay();

        if ($visitDateOnly->greaterThanOrEqualTo($eventDateOnly)) {
            return back()->withErrors(['visit_date' => 'The Site Visit must be scheduled before your event date.']);
        }

        foreach ($visits as $v) {
            $v->update([
                'visit_date' => $request->visit_date,
                'status'     => VisitSchedule::STATUS_RESCHEDULED,
            ]);
        }

        \App\Services\NotificationService::visitRescheduled($visit->fresh('booking'));

        return back()->with('success', 'Visit rescheduled.');
    }

    // Admin marks a visit completed.
    public function complete($id)
    {
        $visit = VisitSchedule::with('booking')->findOrFail($id);

        if (!$visit->canComplete()) {
            return back()->withErrors(['visit' => 'This visit can no longer be marked as completed.']);
        }

        foreach ($this->visitGroup($visit) as $v) {
            if ($v->canComplete()) {
                $v->update(['status' => VisitSchedule::STATUS_COMPLETED]);
            }
        }

        \App\Services\NotificationService::visitCompleted($visit->fresh('booking'));

        return back()->with('success', 'Visit marked as completed.');
    }

    // Admin removes a visit from the list (only hidden, not deleted).
    public function destroy($id)
    {
        $visit = VisitSchedule::with('booking')->findOrFail($id);

        if (!$visit->canRemove()) {
            return back()->withErrors(['visit' => 'This visit still belongs to an upcoming reservation and can\'t be removed.']);
        }

        foreach ($this->visitGroup($visit) as $v) {
            if ($v->canRemove()) {
                $v->delete();
            }
        }

        return back()->with('success', 'Visit removed from the list.');
    }
}
