<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BlockedDate;
use App\Services\BookingAutoCancelService;
use App\Services\BookingStatusService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    // Show booking form with calendar
    public function index()
    {
        // Visit Schedule is a mandatory follow-up step to a just-completed
        // booking, so keep the "booking just succeeded" flash data alive for
        // as many round-trips as it takes — otherwise a failed visit-schedule
        // submission (invalid date, etc.) would redirect back here, the flash
        // data would have already aged out, and the modal (plus the booking
        // it needs to attach the visit to) would silently disappear.
        if (session('booking_success')) {
            session()->keep(['booking_success', 'new_booking_id', 'booking_number']);
        }

        // Packages only change via admin CRUD (which busts this key), so it's
        // safe to skip the DB round-trip on every booking-page visit.
        $packages = Cache::remember('packages.all', 300, fn () => \App\Models\Package::all());

        // Get all dates that have an APPROVED booking (one event per day rule).
        // Room-type packages are excluded — they're governed by per-room
        // availability instead, and shouldn't grey out the calendar venue-wide.
        $approvedDates = Booking::where('status', 'approved')
            ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
            ->pluck('event_date')
            ->map(fn($date) => $date->timezone('Asia/Manila')->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        // Get all blocked dates (apply globally) with reasons
        $blockedDates = BlockedDate::all(['date', 'reason'])
            ->mapWithKeys(fn($item) => [
                $item->date->timezone('Asia/Manila')->format('Y-m-d') => $item->reason ?? 'This date has been blocked by the venue admin (e.g. maintenance, private hold, or holiday).'
            ])
            ->toArray();

        return view('booking', compact('packages', 'approvedDates', 'blockedDates'));
    }

    // Store new booking
    public function store(Request $request)
    {
        $request->validate([
            'event_type'  => 'required|string|in:Birthday,Wedding,Debut,Baptismal,Christening,Christmas Party,Corporate Event,Reunion,Others',
            'event_type_other' => 'required_if:event_type,Others|nullable|string|max:255',
            'package'     => 'required|string',
            // after_or_equal (not after): a same-day booking is allowed to be
            // created — it's then subject to the same-day-unpaid auto-cancel
            // rule (BookingAutoCancelService) rather than being blocked here.
            'event_date'  => 'required|date|after_or_equal:today',
            'guest_count' => 'required|integer|min:1',
            'notes'       => 'nullable|string|max:1000',
            'total_amount' => 'required|numeric|min:0',
            'payment_option' => 'required|in:' . implode(',', Booking::PAYMENT_OPTIONS),
            'terms'        => 'accepted',
        ]);

        // Resolve event type: if "Others" was selected, use the custom value
        $eventType = $request->event_type === 'Others' ? $request->event_type_other : $request->event_type;

        $downPaymentAmount = Booking::calculateDownPayment((float) $request->total_amount);

        $package = \App\Models\Package::where('name', $request->package)->first();

        if (!$package) {
            return back()->withErrors(['package' => 'The selected package does not exist.'])->withInput();
        }

        if ($request->guest_count > $package->max_guests) {
            return back()->withErrors(['guest_count' => 'Guest count exceeds the maximum allowed for the ' . $package->name . ' package (Max: ' . $package->max_guests . ').'])->withInput();
        }

        $isBlocked = BlockedDate::where('date', $request->event_date)->exists();

        if ($isBlocked) {
            return back()->withErrors(['event_date' => 'This date is already reserved for another event. Please choose another available date.'])->withInput();
        }

        $isRoomPackage = Booking::isRoomPackage($request->package);

        if ($isRoomPackage) {
            // A specific physical room is required, and it must belong to
            // this package's room group — never trust the submitted value.
            $request->validate([
                'room_number' => ['required', 'string', 'in:' . implode(',', Booking::roomsFor($request->package))],
            ]);

            // Backend re-check — the room may have been taken by someone else
            // since the page loaded (do not rely on the frontend list alone).
            if (!Booking::isRoomAvailable($request->package, $request->event_date, $request->room_number)) {
                return back()->withErrors(['room_number' => 'This room is no longer available for the selected date. Please select another available room.'])->withInput();
            }
        } else {
            // One Event Per Day (venue-wide): only applies to non-room
            // packages — room bookings are governed by per-room availability.
            $hasApprovedBooking = Booking::where('event_date', $request->event_date)
                ->where('status', 'approved')
                ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
                ->exists();

            if ($hasApprovedBooking) {
                return back()->withErrors(['event_date' => 'This date is already reserved for another event. Please choose another available date.'])->withInput();
            }
        }

        // Generate unique booking number: LDP-YYYYMMDD-XXXX
        $todayStr = now()->format('Ymd');
        $countToday = Booking::whereDate('created_at', now()->today())->count();
        $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
        $bookingNumber = "LDP-{$todayStr}-{$sequence}";

        try {
            $booking = Booking::create([
                'user_id'     => Auth::id(),
                'booking_number' => $bookingNumber,
                'event_type'  => $eventType,
                'package'     => $request->package,
                'room_number' => $isRoomPackage ? $request->room_number : null,
                'event_date'  => $request->event_date,
                'guest_count' => $request->guest_count,
                'notes'       => $request->notes,
                'status'      => 'pending',
                'total_amount' => $request->total_amount,
                'down_payment_amount' => $downPaymentAmount,
                'payment_option' => $request->payment_option,
                'payment_status' => 'unpaid',
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // The DB-level partial unique index caught a race the check above
            // missed (two near-simultaneous submissions for the same room).
            return back()->withErrors(['room_number' => 'This room was just booked by another customer. Please select another available room.'])->withInput();
        }

        NotificationService::bookingSubmitted($booking);

        return redirect()->route('booking')->with('booking_success', true)->with('new_booking_id', $booking->id)->with('booking_number', $booking->booking_number);
    }

    // AJAX: Check if a date is available (One Event Per Day)
    public function checkDate(Request $request)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json(['available' => false, 'reason' => 'No date provided.']);
        }

        $isBlocked = BlockedDate::where('date', $date)->exists();
        if ($isBlocked) {
            $blocked = BlockedDate::where('date', $date)->first();
            return response()->json([
                'available' => false,
                'reason' => $blocked->reason ?? 'This date has been blocked by the venue admin (e.g. maintenance, private hold, or holiday).',
            ]);
        }

        $hasApproved = Booking::where('event_date', $date)
            ->where('status', 'approved')
            ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
            ->exists();

        if ($hasApproved) {
            return response()->json([
                'available' => false,
                'reason' => 'This date is already reserved for another event. Please choose another available date.',
            ]);
        }

        return response()->json(['available' => true]);
    }

    // AJAX: per-room availability for every room-type package, for one date
    public function checkRoomAvailability(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        return response()->json(Booking::roomAvailabilityForDate($request->query('date')));
    }

    // Booking success page
    public function success()
    {
        $booking = null;
        if (session('new_booking_id')) {
            $booking = Booking::find(session('new_booking_id'));
        }
        return view('booking-success', compact('booking'));
    }

    // Profile and Bookings page
    public function profile()
    {
        // No cron runs in production yet, so sweep stale bookings here too —
        // see BookingAutoCancelService for why this needs to happen somewhere.
        BookingAutoCancelService::run();

        $user = Auth::user();
        // Eager-load: the view calls $booking->visitSchedules->first() and
        // hasConfirmedPayment() per row, which would otherwise fire one extra
        // query per booking (N+1).
        $bookings = Booking::with(['visitSchedules', 'payments'])
            ->where('user_id', $user->id)
            ->orderBy('event_date', 'desc')
            ->get();

        return view('profile', compact('user', 'bookings'));
    }

    // Cancel own booking (Client action)
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Once a Down Payment or Full Payment has been confirmed, the
        // reservation is locked in — the customer can no longer cancel it
        // themselves, only submit a reschedule request for the admin to review.
        if ($booking->hasConfirmedPayment()) {
            return back()->withErrors(['cancel' => 'This booking has a confirmed payment and can no longer be cancelled. Please submit a reschedule request instead.']);
        }

        if (!BookingStatusService::transition($booking, Booking::STATUS_CANCELLED)) {
            return back()->withErrors(['cancel' => BookingStatusService::failureReason($booking, Booking::STATUS_CANCELLED)]);
        }

        // Customer's own "Booking Cancelled" notification comes from the
        // transition above; this is the extra heads-up for the admins.
        NotificationService::bookingCancelledByCustomer($booking);

        return back()->with('success', 'Your booking has been cancelled.');
    }

    // Submit reschedule request (Client action)
    public function submitReschedule(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Mirrors the view's own gate (the Reschedule button only ever shows
        // for an approved booking) so a direct/manual request can't request a
        // reschedule the UI would never have offered in the first place.
        if ($booking->status !== Booking::STATUS_APPROVED) {
            return back()->withErrors(['reschedule' => 'Only an approved booking can be rescheduled.']);
        }

        // Prevent submitting if a reschedule is already pending
        if ($booking->reschedule_status === 'pending') {
            return back()->withErrors(['reschedule' => 'A reschedule request is already pending for this booking.']);
        }

        $request->validate([
            'requested_event_date' => 'required|date|after_or_equal:today',
            'requested_visit_date' => 'required|date|after_or_equal:today|before:requested_event_date',
            'reschedule_reason'    => 'nullable|string|max:1000',
        ], [
            'requested_visit_date.before' => 'The Site Visit must be scheduled before your event date.',
            'requested_visit_date.after_or_equal' => 'The Site Visit date cannot be in the past.',
            'requested_event_date.after_or_equal' => 'The event date cannot be in the past.',
        ]);

        // One Event Per Day: check if the requested date already has an approved
        // booking (excluding this one). Doesn't apply to room-type packages —
        // those are governed by per-room availability, not the venue-wide rule.
        $hasConflict = !Booking::isRoomPackage($booking->package) && Booking::where('event_date', $request->requested_event_date)
            ->where('status', 'approved')
            ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($hasConflict) {
            return back()->withErrors(['requested_event_date' => 'This date is already reserved for another event. Please choose another available date.'])->withInput();
        }

        // Fee: FREE for first reschedule, ₱5,000 for subsequent
        $fee = $booking->reschedule_count == 0 ? 0 : 5000;

        $booking->update([
            'reschedule_status'      => 'pending',
            'requested_event_date'   => $request->requested_event_date,
            'requested_visit_date'   => $request->requested_visit_date,
            'reschedule_reason'      => $request->reschedule_reason,
            'reschedule_fee'         => $fee,
        ]);

        NotificationService::rescheduleRequested($booking);

        return back()->with('success', 'Reschedule request submitted successfully.');
    }
}