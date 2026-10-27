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
    // Max bookings one submission can create.
    private const MAX_BOOKINGS_PER_SUBMISSION = 100;

    // Booking page with the calendar.
    public function index()
    {
        // Keep the success data so the visit-schedule modal stays open after a failed submit.
        if (session('booking_success')) {
            session()->keep(['booking_success', 'new_booking_id', 'new_booking_ids', 'booking_number']);
        }

        // Packages are cached for 5 minutes (cleared when an admin edits them).
        $packages = Cache::remember('packages.all', 300, fn () => \App\Models\Package::all());

        // Dates already taken by an approved event (rooms are checked per room).
        $approvedDates = Booking::whereIn('status', Booking::DATE_HOLDING_STATUSES)
            ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
            ->pluck('event_date')
            ->map(fn($date) => $date->timezone('Asia/Manila')->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        // Blocked dates and their reasons.
        $blockedDates = BlockedDate::all(['date', 'reason'])
            ->mapWithKeys(fn($item) => [
                $item->date->timezone('Asia/Manila')->format('Y-m-d') => $item->reason ?? 'This date has been blocked by the venue admin (e.g. maintenance, private hold, or holiday).'
            ])
            ->toArray();

        return view('booking', compact('packages', 'approvedDates', 'blockedDates'));
    }

    // Saves a booking: one or more packages, for one day or a date range.
    public function store(Request $request)
    {
        // A single-package request is treated as a list with one item.
        if (!$request->has('items')) {
            $request->merge(['items' => [$request->only(['occasion', 'event_type', 'event_type_other', 'package', 'room_number', 'guest_count'])]]);
        }

        $request->validate([
            'items'                    => 'required|array|min:1|max:10',
            'items.*.occasion'         => 'required|in:' . implode(',', array_keys(Booking::OCCASIONS)),
            // Event type is only needed for the "Event" occasion.
            'items.*.event_type'       => 'required_if:items.*.occasion,event|nullable|string|in:Birthday,Wedding,Debut,Baptismal,Christening,Christmas Party,Corporate Event,Reunion,Others',
            'items.*.event_type_other' => 'required_if:items.*.event_type,Others|nullable|string|max:255',
            'items.*.package'          => 'required|string',
            'items.*.room_number'      => 'nullable|string',
            'items.*.guest_count'      => 'required|integer|min:1',
            // No same-day bookings, a site visit must fit before the event.
            'event_date'     => 'required|date|after:today',
            // Swimming/Staycation can book a date range.
            'event_end_date' => 'nullable|date|after_or_equal:event_date',
            'notes'          => 'nullable|string|max:1000',
            'payment_option' => 'required|in:' . implode(',', Booking::PAYMENT_OPTIONS),
            // Maya and card are not available in the demo.
            'payment_method' => 'required|in:gcash,venue',
            'terms'          => 'accepted',
        ], [
            'payment_method.required'          => 'Please choose how you will pay.',
            'payment_method.in'                => 'That payment method is not available yet.',
            'items.required'                   => 'Please add at least one package.',
            'items.max'                        => 'You can book up to 10 packages at a time.',
            'items.*.occasion.required'        => 'Please select an occasion.',
            'items.*.occasion.in'              => 'Please select a valid occasion.',
            'items.*.event_type.required_if'   => 'Please select an event type.',
            'items.*.event_type_other.required_if' => 'Please specify the event type.',
            'items.*.package.required'         => 'Please select a package.',
            'items.*.guest_count.required'     => 'Please enter the number of guests.',
        ]);

        // Every day being booked (one day or the whole range).
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

        // Safety limit on how many bookings one request can create.
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

            // The package must match the chosen occasion.
            if (Booking::occasionForPackage($package->name) !== $item['occasion']) {
                return back()->withErrors(['package' => 'Please select a package that matches the chosen occasion.'])->withInput();
            }

            if ((int) $item['guest_count'] > $package->max_guests) {
                return back()->withErrors(['guest_count' => 'Guest count exceeds the maximum allowed for the ' . $package->name . ' package (Max: ' . $package->max_guests . ').'])->withInput();
            }

            $isRoomPackage = Booking::isRoomPackage($package->name);
            $roomNumber = $isRoomPackage ? ($item['room_number'] ?? null) : null;

            // The room must belong to this package.
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
                    // Room may have been taken since the page loaded.
                    if (!Booking::isRoomAvailable($package->name, $date, $roomNumber)) {
                        return back()->withErrors(['room_number' => $package->name . ' ' . $roomNumber . ' is not available on ' . $label($date) . '. Please choose another room or other dates.'])->withInput();
                    }
                } else {
                    // A package can only be booked once per date.
                    if (!Booking::isPackageAvailable($package->name, $date)) {
                        return back()->withErrors(['package' => $package->name . ' is already booked on ' . $label($date) . '. Please choose another date or package.'])->withInput();
                    }

                    // One event per day: another customer's approved event blocks the date.
                    $takenByOthers = Booking::where('event_date', $date)
                        ->whereIn('status', Booking::DATE_HOLDING_STATUSES)
                        ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
                        ->where('user_id', '!=', Auth::id())
                        ->exists();

                    if ($takenByOthers) {
                        return back()->withErrors(['event_date' => $label($date) . ' is already reserved for another event. Please choose other dates.'])->withInput();
                    }
                }

                // Price always comes from the package (weekend price on Sat/Sun).
                $totalAmount = $package->priceForDate($date);

                $prepared[] = [
                    'event_type'  => $eventType,
                    'package'     => $package->name,
                    'room_number' => $roomNumber,
                    'event_date'  => $date,
                    'guest_count' => (int) $item['guest_count'],
                    'total_amount' => $totalAmount,
                    'down_payment_amount' => Booking::calculateDownPayment($totalAmount),
                    // Packages with a fixed time slot get it right away.
                    'start_time'  => $package->start_time && $package->end_time ? $package->start_time : null,
                    'end_time'    => $package->start_time && $package->end_time ? $package->end_time : null,
                ];
            }
        }

        // Bookings made together share a group id.
        $group = count($prepared) > 1 ? (string) \Illuminate\Support\Str::uuid() : null;

        // Create all bookings in one transaction; retry if a booking number clashes.
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

        // Online payment (demo): go to the GCash page first.
        if ($request->payment_method === 'gcash') {
            session(['demo_pay_ids' => array_map(fn ($b) => $b->id, $bookings)]);
            return redirect()->route('payment.demo.show');
        }

        return redirect()->route('booking')
            ->with('booking_success', true)
            ->with('new_booking_id', $bookings[0]->id)
            ->with('new_booking_ids', array_map(fn ($b) => $b->id, $bookings))
            ->with('booking_number', implode(', ', array_map(fn ($b) => $b->booking_number, $bookings)));
    }

    // AJAX: is a date available?
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

        // Rooms are not affected by the one-event-per-day rule.
        if ($request->boolean('rooms_only')) {
            return response()->json(['available' => true]);
        }

        $hasApproved = Booking::where('event_date', $date)
            ->whereIn('status', Booking::DATE_HOLDING_STATUSES)
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

    // AJAX: room and package availability for a date.
    public function checkRoomAvailability(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        return response()->json(Booking::roomAvailabilityForDate($request->query('date')));
    }

    // Booking success page.
    public function success()
    {
        $booking = null;
        if (session('new_booking_id')) {
            $booking = Booking::find(session('new_booking_id'));
        }
        return view('booking-success', compact('booking'));
    }

    // Customer profile and reservations.
    public function profile()
    {
        // Run the automatic booking rules (at most once a minute).
        BookingAutoCancelService::runThrottled();

        $user = Auth::user();
        // Load visits and payments together to avoid extra queries.
        $bookings = Booking::with(['visitSchedules', 'payments'])
            ->where('user_id', $user->id)
            ->whereNull('hidden_by_customer_at') // deleted from "My Reservations"
            ->orderBy('event_date', 'desc')
            ->get();

        // Completed bookings count for the account tier, even hidden ones.
        $completedCount = Booking::where('user_id', $user->id)
            ->where('status', Booking::STATUS_COMPLETED)
            ->count();

        return view('profile', compact('user', 'bookings', 'completedCount'));
    }

    // Customer hides a finished booking from "My Reservations" (admin still sees it).
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

    // Customer cancels their own booking.
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Paid bookings can't be cancelled by the customer, only rescheduled.
        if ($booking->hasConfirmedPayment()) {
            return back()->withErrors(['cancel' => 'This booking has a confirmed payment and can no longer be cancelled. Please submit a reschedule request instead.']);
        }

        if (!BookingStatusService::transition($booking, Booking::STATUS_CANCELLED)) {
            return back()->withErrors(['cancel' => BookingStatusService::failureReason($booking, Booking::STATUS_CANCELLED)]);
        }

        // Let the admins know the customer cancelled.
        NotificationService::bookingCancelledByCustomer($booking);

        return back()->with('success', 'Your booking has been cancelled.');
    }

    // Customer requests a reschedule.
    public function submitReschedule(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Only approved bookings can be rescheduled.
        if ($booking->status !== Booking::STATUS_APPROVED) {
            return back()->withErrors(['reschedule' => 'Only an approved booking can be rescheduled.']);
        }

        if ($booking->reschedule_status === 'pending') {
            return back()->withErrors(['reschedule' => 'A reschedule request is already pending for this booking.']);
        }

        $request->validate([
            'requested_event_date' => 'required|date|after:today',
            'requested_visit_date' => 'required|date|after_or_equal:today|before:requested_event_date',
            'reschedule_reason'    => 'nullable|string|max:1000',
        ], [
            'requested_visit_date.before' => 'The Site Visit must be scheduled before your event date.',
            'requested_visit_date.after_or_equal' => 'The Site Visit date cannot be in the past.',
            'requested_event_date.after' => 'The event date must be tomorrow or later.',
        ]);

        // One event per day check for the new date (not for rooms).
        $hasConflict = !Booking::isRoomPackage($booking->package) && Booking::where('event_date', $request->requested_event_date)
            ->whereIn('status', Booking::DATE_HOLDING_STATUSES)
            ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($hasConflict) {
            return back()->withErrors(['requested_event_date' => 'This date is already reserved for another event. Please choose another available date.'])->withInput();
        }

        // The package or room must be free on the new date.
        if (!$booking->slotAvailableOn($request->requested_event_date)) {
            return back()->withErrors(['requested_event_date' => $booking->package . ($booking->room_number ? ' ' . $booking->room_number : '') . ' is already booked on that date. Please choose another date.'])->withInput();
        }

        // First reschedule is free, then ₱5,000 each.
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