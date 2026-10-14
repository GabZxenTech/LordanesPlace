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
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /** Most bookings (packages x days) one submission may create. */
    private const MAX_BOOKINGS_PER_SUBMISSION = 100;

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
            session()->keep(['booking_success', 'new_booking_id', 'new_booking_ids', 'booking_number']);
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

    // Store new booking — one or more packages, for one date or a range of
    // days (Swimming/Staycation). Each package-day becomes its own booking
    // (own booking number, status and payments); they are created together
    // or not at all, linked by booking_group.
    public function store(Request $request)
    {
        // A plain single-package submission (no items[]) is treated as a
        // one-item list, so both forms of the request go through one path.
        if (!$request->has('items')) {
            $request->merge(['items' => [$request->only(['occasion', 'event_type', 'event_type_other', 'package', 'room_number', 'guest_count'])]]);
        }

        $request->validate([
            'items'                    => 'required|array|min:1|max:10',
            'items.*.occasion'         => 'required|in:' . implode(',', array_keys(Booking::OCCASIONS)),
            // Event Type only applies to the "Event" occasion; Swimming and
            // Staycation bookings don't choose one (the field is disabled).
            'items.*.event_type'       => 'required_if:items.*.occasion,event|nullable|string|in:Birthday,Wedding,Debut,Baptismal,Christening,Christmas Party,Corporate Event,Reunion,Others',
            'items.*.event_type_other' => 'required_if:items.*.event_type,Others|nullable|string|max:255',
            'items.*.package'          => 'required|string',
            'items.*.room_number'      => 'nullable|string',
            'items.*.guest_count'      => 'required|integer|min:1',
            // after_or_equal (not after): a same-day booking is allowed to be
            // created — it's then subject to the same-day-unpaid auto-cancel
            // rule (BookingAutoCancelService) rather than being blocked here.
            'event_date'     => 'required|date|after_or_equal:today',
            // Swimming/Staycation may book a range of days (inclusive).
            'event_end_date' => 'nullable|date|after_or_equal:event_date',
            'notes'          => 'nullable|string|max:1000',
            'payment_option' => 'required|in:' . implode(',', Booking::PAYMENT_OPTIONS),
            'terms'          => 'accepted',
        ], [
            'items.required'                   => 'Please add at least one package.',
            'items.max'                        => 'You can book up to 10 packages at a time.',
            'items.*.occasion.required'        => 'Please select an occasion.',
            'items.*.occasion.in'              => 'Please select a valid occasion.',
            'items.*.event_type.required_if'   => 'Please select an event type.',
            'items.*.event_type_other.required_if' => 'Please specify the event type.',
            'items.*.package.required'         => 'Please select a package.',
            'items.*.guest_count.required'     => 'Please enter the number of guests.',
        ]);

        // The days being booked: one day, or every day from event_date to
        // event_end_date inclusive (a Swimming/Staycation date range — each
        // day is paid separately). Each package is booked once per day.
        $start = \Carbon\Carbon::parse($request->event_date)->startOfDay();
        $end = $request->filled('event_end_date')
            ? \Carbon\Carbon::parse($request->event_end_date)->startOfDay()
            : $start->copy();
        $dates = [];
        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $dates[] = $day->toDateString();
        }
        $label = fn (string $date) => \Carbon\Carbon::parse($date)->format('M d, Y');

        if (count($dates) > 1 && collect($request->items)->contains('occasion', 'event')) {
            return back()->withErrors(['event_date' => 'Event packages are booked for a single date. Pick one day, or remove the Event package from your list.'])->withInput();
        }

        // Safety cap on how many bookings one submission can create.
        if (count($dates) * count($request->items) > self::MAX_BOOKINGS_PER_SUBMISSION) {
            return back()->withErrors(['event_date' => 'That is too many days and packages for one booking. Please split it into smaller bookings.'])->withInput();
        }

        $blocked = BlockedDate::whereIn('date', $dates)->orderBy('date')->first();
        if ($blocked) {
            return back()->withErrors(['event_date' => $label($blocked->date->toDateString()) . ' is not available' . ($blocked->reason ? ' (' . $blocked->reason . ')' : '') . '. Please choose other dates.'])->withInput();
        }

        $packages = \App\Models\Package::whereIn('name', collect($request->items)->pluck('package'))->get()->keyBy('name');
        $prepared = [];
        $seen = [];
        $eventItems = 0;

        foreach ($request->items as $item) {
            $package = $packages->get($item['package']);

            if (!$package) {
                return back()->withErrors(['package' => 'The selected package does not exist.'])->withInput();
            }

            // The form only lists packages for the chosen occasion; enforce
            // the same pairing here so a crafted request can't mix them.
            if (Booking::occasionForPackage($package->name) !== $item['occasion']) {
                return back()->withErrors(['package' => 'Please select a package that matches the chosen occasion.'])->withInput();
            }

            if ((int) $item['guest_count'] > $package->max_guests) {
                return back()->withErrors(['guest_count' => 'Guest count exceeds the maximum allowed for the ' . $package->name . ' package (Max: ' . $package->max_guests . ').'])->withInput();
            }

            $isRoomPackage = Booking::isRoomPackage($package->name);
            $roomNumber = $isRoomPackage ? ($item['room_number'] ?? null) : null;

            // A specific physical room is required, and it must belong to
            // this package's room group — never trust the submitted value.
            if ($isRoomPackage && !in_array($roomNumber, Booking::roomsFor($package->name), true)) {
                return back()->withErrors(['room_number' => 'Please select a valid room for ' . $package->name . '.'])->withInput();
            }

            $key = $package->name . '|' . $roomNumber;
            if (isset($seen[$key])) {
                return back()->withErrors(['package' => $package->name . ($roomNumber ? ' ' . $roomNumber : '') . ' is already in your list.'])->withInput();
            }
            $seen[$key] = true;

            if ($item['occasion'] === 'event' && ++$eventItems > 1) {
                return back()->withErrors(['package' => 'Only one Event package can be booked per day.'])->withInput();
            }

            $eventType = $item['occasion'] === 'event'
                ? ($item['event_type'] === 'Others' ? $item['event_type_other'] : $item['event_type'])
                : Booking::OCCASIONS[$item['occasion']];

            foreach ($dates as $date) {
                if ($isRoomPackage) {
                    // Backend re-check — the room may have been taken by
                    // someone else since the page loaded.
                    if (!Booking::isRoomAvailable($package->name, $date, $roomNumber)) {
                        return back()->withErrors(['room_number' => $package->name . ' ' . $roomNumber . ' is not available on ' . $label($date) . '. Please choose another room or other dates.'])->withInput();
                    }
                } else {
                    // The same package can't be booked twice for one date —
                    // the first active booking (pending counts) holds it.
                    if (!Booking::isPackageAvailable($package->name, $date)) {
                        return back()->withErrors(['package' => $package->name . ' is already booked on ' . $label($date) . '. Please choose another date or package.'])->withInput();
                    }

                    // One Event Per Day (venue-wide): another customer's
                    // approved non-room booking holds the date. The same
                    // customer's own packages on that day don't block each other.
                    $takenByOthers = Booking::where('event_date', $date)
                        ->where('status', 'approved')
                        ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
                        ->where('user_id', '!=', Auth::id())
                        ->exists();

                    if ($takenByOthers) {
                        return back()->withErrors(['event_date' => $label($date) . ' is already reserved for another event. Please choose other dates.'])->withInput();
                    }
                }

                // The price always comes from the package record (weekend
                // rate on Sat/Sun when the package has one), never the request.
                $totalAmount = $package->priceForDate($date);

                $prepared[] = [
                    'event_type'  => $eventType,
                    'package'     => $package->name,
                    'room_number' => $roomNumber,
                    'event_date'  => $date,
                    'guest_count' => (int) $item['guest_count'],
                    'total_amount' => $totalAmount,
                    'down_payment_amount' => Booking::calculateDownPayment($totalAmount),
                    // Packages with a fixed time slot (e.g. the swimming
                    // packages) get it right away; others stay unset until
                    // the admin assigns a time.
                    'start_time'  => $package->start_time && $package->end_time ? $package->start_time : null,
                    'end_time'    => $package->start_time && $package->end_time ? $package->end_time : null,
                ];
            }
        }

        // Bookings made together share a group id so they can be shown and
        // handled as one reservation.
        $group = count($prepared) > 1 ? (string) \Illuminate\Support\Str::uuid() : null;

        // All bookings are created in one transaction. A booking-number clash
        // (two customers booking at the same moment) retries the whole set
        // with fresh numbers; a room clash (partial unique index on
        // room/date) is reported to the customer.
        $bookings = null;

        for ($attempt = 1; $attempt <= 3 && !$bookings; $attempt++) {
            try {
                $bookings = DB::transaction(function () use ($prepared, $request, $group) {
                    return array_map(fn ($data) => Booking::create($data + [
                        'user_id'        => Auth::id(),
                        'booking_number' => Booking::nextBookingNumber(),
                        'booking_group'  => $group,
                        'notes'          => $request->notes,
                        'status'         => 'pending',
                        'payment_option' => $request->payment_option,
                        'payment_status' => 'unpaid',
                    ]), $prepared);
                });
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                foreach ($prepared as $data) {
                    if ($data['room_number'] && !Booking::isRoomAvailable($data['package'], $data['event_date'], $data['room_number'])) {
                        return back()->withErrors(['room_number' => $data['package'] . ' ' . $data['room_number'] . ' on ' . $label($data['event_date']) . ' was just booked by another customer. Please choose another room or other dates.'])->withInput();
                    }
                    if (!$data['room_number'] && !Booking::isPackageAvailable($data['package'], $data['event_date'])) {
                        return back()->withErrors(['package' => $data['package'] . ' on ' . $label($data['event_date']) . ' was just booked by another customer. Please choose another date or package.'])->withInput();
                    }
                }
            }
        }

        if (!$bookings) {
            return back()->withErrors(['event_date' => 'We could not complete your booking right now. Please try again.'])->withInput();
        }

        foreach ($bookings as $booking) {
            NotificationService::bookingSubmitted($booking);
        }

        return redirect()->route('booking')
            ->with('booking_success', true)
            ->with('new_booking_id', $bookings[0]->id)
            ->with('new_booking_ids', array_map(fn ($b) => $b->id, $bookings))
            ->with('booking_number', implode(', ', array_map(fn ($b) => $b->booking_number, $bookings)));
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

        // Room-only (Staycation) bookings aren't affected by the one-event-
        // per-day rule — rooms have their own per-room availability.
        if ($request->boolean('rooms_only')) {
            return response()->json(['available' => true]);
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
            ->whereNull('hidden_by_customer_at') // deleted from "My Reservations"
            ->orderBy('event_date', 'desc')
            ->get();

        // Account tier counts every completed booking, including ones the
        // customer has since deleted from their list.
        $completedCount = Booking::where('user_id', $user->id)
            ->where('status', Booking::STATUS_COMPLETED)
            ->count();

        return view('profile', compact('user', 'bookings', 'completedCount'));
    }

    // Delete a finished booking from "My Reservations" (Client action). It is
    // only hidden from the customer — the booking, payments and visits stay
    // for the admin, reports and history. Can't be undone by the customer.
    public function deleteForCustomer($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereNull('hidden_by_customer_at')
            ->firstOrFail();

        if (!$booking->customerCanDelete()) {
            return back()->withErrors(['delete' => 'Only cancelled, rejected or past bookings can be deleted. Completed bookings stay in your history, and upcoming ones must be cancelled first.']);
        }

        $booking->forceFill(['hidden_by_customer_at' => now()])->save();

        return back()->with('success', 'Booking #' . $booking->booking_number . ' was deleted from your reservations.');
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

        // The same package (or room) can't already be booked on the new date.
        if (!$booking->slotAvailableOn($request->requested_event_date)) {
            return back()->withErrors(['requested_event_date' => $booking->package . ($booking->room_number ? ' ' . $booking->room_number : '') . ' is already booked on that date. Please choose another date.'])->withInput();
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