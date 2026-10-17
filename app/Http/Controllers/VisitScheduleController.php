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
        // Generate the allowed time values: 08:00 – 17:00 at 30-min intervals
        $allowedTimes = [];
        for ($h = 8; $h <= 17; $h++) {
            foreach (['00', '30'] as $m) {
                if ($h === 17 && $m === '30') continue;
                $allowedTimes[] = sprintf('%02d:%s', $h, $m);
            }
        }

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            // A multi-package booking sends every booking from that submission
            // so the one visit applies to all of them.
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


        // Only the customer's own booking — the posted booking_id is never
        // trusted on its own (same ownership scoping as cancel/reschedule).
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $visitDateOnly = \Carbon\Carbon::parse($request->visit_date)->startOfDay();
        $eventDateOnly = \Carbon\Carbon::parse($booking->event_date)->startOfDay();

        // Validation: Visit date must be strictly BEFORE event date (cannot be same day or after)
        if ($visitDateOnly->greaterThanOrEqualTo($eventDateOnly)) {
            return back()->withErrors(['visit_date' => 'The Site Visit must be scheduled before your event date.'])->withInput();
        }

        $visitDateTime = \Carbon\Carbon::parse($request->visit_date . ' ' . $request->visit_time);

        // Same ownership scoping for the other bookings from the same
        // submission (several packages and/or a range of days). booking_id is
        // the first one — the earliest day — so the date check above covers
        // the whole group.
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

    // Client view of their own schedules
    public function index()
    {
        // "Remove" in the admin panel only hides a visit from the admin list.
        $visits = VisitSchedule::withTrashed()
            ->where('user_id', Auth::id())
            ->with('booking')
            ->orderBy('visit_date', 'asc')
            ->get();
        return view('my-visits', compact('visits'));
    }

    // Admin view of all schedules
    public function adminIndex()
    {
        // Mark overdue visits "missed" (and apply the other automatic rules)
        // before listing. Throttled to once a minute site-wide, so a status
        // is at most a minute behind.
        \App\Services\BookingAutoCancelService::runThrottled();

        $visits = VisitSchedule::with(['user', 'booking'])->orderBy('visit_date', 'asc')->get();
        return view('admin.visits', compact('visits'));
    }

    /**
     * The visit plus its twins: bookings made together (a date range or
     * several packages share a booking_group) get one visit row each, at the
     * same time. The admin sees them as one row, so every action applies to
     * all of them.
     */
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

    // Admin confirm visit — only a visit still waiting for confirmation.
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

        // One notification per visit group (bookings made together share it).
        \App\Services\NotificationService::visitConfirmed($visit->fresh('booking'));

        return back()->with('success', 'Visit confirmed.');
    }

    // Admin reschedule visit — updates the same record(s) (no duplicate rows).
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

        // The visit must come before the earliest event day it covers.
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

    // Admin mark completed — final; a completed visit is never marked missed.
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

    // Admin remove — hides old visits from the list (soft delete). The
    // bookings, payments and the visit rows themselves stay in the database.
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
