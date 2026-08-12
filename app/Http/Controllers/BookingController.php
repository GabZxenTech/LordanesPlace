<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BlockedDate;
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
        // Packages only change via admin CRUD (which busts this key), so it's
        // safe to skip the DB round-trip on every booking-page visit.
        $packages = Cache::remember('packages.all', 300, fn () => \App\Models\Package::all());

        // Get all dates that have an APPROVED booking (one event per day rule)
        $approvedDates = Booking::where('status', 'approved')
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
            'event_date'  => 'required|date|after:today',
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

        // One Event Per Day: check if an approved booking already exists on this date
        $hasApprovedBooking = Booking::where('event_date', $request->event_date)
            ->where('status', 'approved')
            ->exists();

        $isBlocked = BlockedDate::where('date', $request->event_date)->exists();

        if ($hasApprovedBooking || $isBlocked) {
            return back()->withErrors(['event_date' => 'This date is already reserved for another event. Please choose another available date.'])->withInput();
        }

        // Generate unique booking number: LDP-YYYYMMDD-XXXX
        $todayStr = now()->format('Ymd');
        $countToday = Booking::whereDate('created_at', now()->today())->count();
        $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
        $bookingNumber = "LDP-{$todayStr}-{$sequence}";

        $booking = Booking::create([
            'user_id'     => Auth::id(),
            'booking_number' => $bookingNumber,
            'event_type'  => $eventType,
            'package'     => $request->package,
            'event_date'  => $request->event_date,
            'guest_count' => $request->guest_count,
            'notes'       => $request->notes,
            'status'      => 'pending',
            'total_amount' => $request->total_amount,
            'down_payment_amount' => $downPaymentAmount,
            'payment_option' => $request->payment_option,
            'payment_status' => 'unpaid',
        ]);

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
            ->exists();

        if ($hasApproved) {
            return response()->json([
                'available' => false,
                'reason' => 'This date is already reserved for another event. Please choose another available date.',
            ]);
        }

        return response()->json(['available' => true]);
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

        // One Event Per Day: check if the requested date already has an approved booking (excluding this one)
        $hasConflict = Booking::where('event_date', $request->requested_event_date)
            ->where('status', 'approved')
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