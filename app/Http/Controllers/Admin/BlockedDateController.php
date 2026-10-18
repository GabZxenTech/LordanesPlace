<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Booking;
use App\Services\BookingAutoCancelService;
use App\Services\BookingStatusService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlockedDateController extends Controller
{
    // Admin schedule: bookings and blocked dates.
    public function index(Request $request)
    {
        // Run the automatic booking rules (at most once a minute).
        BookingAutoCancelService::runThrottled();

        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        // Ignore invalid month values.
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        $selectedMonth = \Carbon\Carbon::createFromDate($year, $month, 1);

        // Load users and payments together to avoid extra queries.
        $bookings = Booking::with(['user', 'payments'])
            ->whereYear('event_date', $selectedMonth->year)
            ->whereMonth('event_date', $selectedMonth->month)
            ->orderBy('event_date', 'asc')
            ->get();
        // Only show today's and upcoming blocked dates.
        $blockedDates = BlockedDate::whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.schedule', compact('bookings', 'blockedDates', 'selectedMonth'));
    }

    // Block a date.
    public function store(Request $request)
    {
        $request->validate([
            'date'   => 'required|date|after_or_equal:today|unique:blocked_dates,date',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedDate::create([
            'date'   => $request->date,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Date blocked successfully.');
    }

    // Unblock a date.
    public function destroy($id)
    {
        BlockedDate::findOrFail($id)->delete();
        return back()->with('success', 'Date unblocked successfully.');
    }

    // Approve a booking.
    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);

        // One event per day: another customer's approved event blocks the date (not for rooms).
        if (!Booking::isRoomPackage($booking->package)) {
            $hasConflict = Booking::where('event_date', $booking->event_date)
                ->where('status', 'approved')
                ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
                ->where('id', '!=', $booking->id)
                ->where('user_id', '!=', $booking->user_id)
                ->exists();

            if ($hasConflict) {
                return back()->withErrors(['error' => 'Cannot approve: another event is already approved on ' . $booking->event_date->format('F d, Y') . '. Only one event per day is allowed.']);
            }
        }

        return $this->applyStatus($booking, Booking::STATUS_APPROVED, 'Booking approved.');
    }

    // Reject a booking.
    public function rejectBooking(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->cancellation_reason = $request->cancellation_reason;
        $booking->save();

        return $this->applyStatus($booking, Booking::STATUS_REJECTED, 'Booking rejected.');
    }

    // Admin cancels a booking.
    public function cancelBooking(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->cancellation_reason = $request->cancellation_reason;
        $booking->save();

        return $this->applyStatus($booking, Booking::STATUS_CANCELLED, 'Booking cancelled.');
    }

    // Mark a booking as ongoing.
    public function markOngoing($id)
    {
        return $this->applyStatus(Booking::findOrFail($id), Booking::STATUS_ONGOING, 'Booking marked as ongoing.');
    }

    // Mark a booking as completed.
    public function markCompleted($id)
    {
        return $this->applyStatus(Booking::findOrFail($id), Booking::STATUS_COMPLETED, 'Booking marked as completed.');
    }

    // Change a booking's status from a dropdown.
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(Booking::STATUSES)],
        ]);

        $booking = Booking::findOrFail($id);

        return $this->applyStatus($booking, $request->status, 'Booking status updated to ' . $request->status . '.');
    }

    // Checks a status change is allowed, saves it and notifies the customer.
    private function applyStatus(Booking $booking, string $status, string $successMessage)
    {
        if (!BookingStatusService::transition($booking, $status)) {
            return back()->withErrors(['error' => BookingStatusService::failureReason($booking, $status)]);
        }

        return back()->with('success', $successMessage);
    }

    // Records a payment received by the admin (optionally with a price adjustment).
    public function recordPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->isCancelledOrRejected()) {
            return back()->withErrors(['amount' => 'Cannot record a payment for a cancelled or rejected booking.']);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:255',
            // Optional price change: positive adds, negative is a discount.
            'price_adjustment'  => 'nullable|numeric|between:-10000000,10000000',
            'adjustment_reason' => 'nullable|string|max:120',
        ]);

        $adjustment = round((float) $request->input('price_adjustment', 0), 2);
        $newTotal = round((float) $booking->total_amount + $adjustment, 2);

        if ($adjustment != 0 && $newTotal < $booking->amountPaid()) {
            return back()->withErrors(['price_adjustment' => 'The adjusted price (₱' . number_format($newTotal, 2) . ') can\'t be lower than what has already been paid (₱' . number_format($booking->amountPaid(), 2) . ').']);
        }

        $remaining = round($newTotal - $booking->amountPaid(), 2);

        if ($remaining <= 0) {
            return back()->withErrors(['amount' => 'This booking is already fully paid — there is no remaining balance to confirm.']);
        }

        if ((float) $request->amount > $remaining + 0.01) {
            return back()->withErrors(['amount' => 'That amount exceeds the remaining balance of ₱' . number_format($remaining, 2) . '.']);
        }

        // The price adjustment is written on the payment note.
        $note = $request->note;
        if ($adjustment != 0) {
            $label = 'Price adjustment ' . ($adjustment > 0 ? '+' : '−') . '₱' . number_format(abs($adjustment), 2)
                . ($request->filled('adjustment_reason') ? ' (' . $request->adjustment_reason . ')' : '');
            $note = mb_substr(trim($label . ($note ? ' · ' . $note : '')), 0, 255);
        }

        // Save the new price and the payment together.
        try {
            $payment = \Illuminate\Support\Facades\DB::transaction(function () use ($booking, $adjustment, $newTotal, $request, $note) {
                if ($adjustment != 0) {
                    $booking->update([
                        'total_amount' => $newTotal,
                        'down_payment_amount' => Booking::calculateDownPayment($newTotal),
                    ]);
                }

                $payment = PaymentService::recordPayment($booking, (float) $request->amount, $note);

                if (!$payment) {
                    throw new \RuntimeException('payment not recorded');
                }

                return $payment;
            }, 1);
        } catch (\RuntimeException $e) {
            $booking->refresh();
            return back()->withErrors(['amount' => 'Payment could not be recorded.']);
        }

        $message = 'Payment of ₱' . number_format($payment->amount, 2) . ' confirmed for ' . $booking->user->name . '.';
        if ($adjustment != 0) {
            $message .= ' New total price: ₱' . number_format($newTotal, 2) . '.';
        }

        return back()->with('success', $message);
    }

    // Admin edits a booking.
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => ['nullable', 'date_format:H:i', function ($attribute, $value, $fail) use ($request) {
                // End time before start time means the next day; equal times are invalid.
                if ($value && $request->filled('start_time') && $value === $request->start_time) {
                    $fail('The end time must be different from the start time.');
                }
            }],
            'guest_count' => 'required|integer|min:1',
            'notes'       => 'nullable|string',
            'package'     => 'required|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // One event per day check when the date changes.
        if ($booking->event_date->format('Y-m-d') !== $request->event_date) {
            $hasConflict = Booking::where('event_date', $request->event_date)
                ->where('status', 'approved')
                ->where('id', '!=', $booking->id)
                ->exists();

            if ($hasConflict) {
                return back()->withErrors(['event_date' => 'Cannot update: another event is already approved on this date. Only one event per day is allowed.'])->withInput();
            }
        }

        // The package or room must be free on the new date.
        if ($booking->event_date->format('Y-m-d') !== $request->event_date || $booking->package !== $request->package) {
            $free = $booking->room_number && Booking::isRoomPackage($request->package)
                ? Booking::isRoomAvailable($request->package, $request->event_date, $booking->room_number, $booking->id)
                : Booking::isPackageAvailable($request->package, $request->event_date, $booking->id);

            if (!$free) {
                return back()->withErrors(['event_date' => 'Cannot update: ' . $request->package . ($booking->room_number ? ' ' . $booking->room_number : '') . ' is already booked on that date.'])->withInput();
            }
        }

        // Only notify the customer if the time or notes actually changed.
        $oldStart = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : null;
        $oldEnd = $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : null;
        $scheduleChanged = $request->start_time !== $oldStart || $request->end_time !== $oldEnd;
        $scheduleNowComplete = $request->filled('start_time') && $request->filled('end_time');
        $notesChanged = $request->filled('notes') && $request->notes !== $booking->notes;

        $booking->update([
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'guest_count' => $request->guest_count,
            'notes'       => $request->notes,
            'package'     => $request->package,
            'total_amount' => $request->total_amount,
            'down_payment_amount' => Booking::calculateDownPayment((float) $request->total_amount),
        ]);

        if ($scheduleNowComplete && $scheduleChanged) {
            NotificationService::scheduleUpdated($booking);
        }

        if ($notesChanged) {
            NotificationService::noteAdded($booking);
        }

        return back()->with('success', 'Booking updated successfully.');
    }

    // Delete a booking.
    public function destroyBooking($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }

    // Pending reschedule requests.
    public function rescheduleRequests()
    {
        $reschedules = Booking::with('user', 'visitSchedules')
            ->where('reschedule_status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.reschedules', compact('reschedules'));
    }

    // Approve a reschedule request.
    public function approveReschedule($id)
    {
        $booking = Booking::with('visitSchedules')->findOrFail($id);

        if ($booking->reschedule_status !== 'pending') {
            return back()->withErrors(['error' => 'This reschedule request is not pending.']);
        }

        if (Booking::isRoomPackage($booking->package)) {
            // The same room must be free on the new date.
            if (!Booking::isRoomAvailable($booking->package, $booking->requested_event_date, $booking->room_number, $booking->id)) {
                return back()->withErrors(['error' => 'Cannot approve reschedule: ' . $booking->package . ' ' . $booking->room_number . ' is no longer available on ' . $booking->requested_event_date->format('F d, Y') . '.']);
            }
        } else {
            // The package must be free on the new date.
            if (!Booking::isPackageAvailable($booking->package, $booking->requested_event_date->toDateString(), $booking->id)) {
                return back()->withErrors(['error' => 'Cannot approve reschedule: ' . $booking->package . ' is already booked on ' . $booking->requested_event_date->format('F d, Y') . '.']);
            }

            // One event per day check for the new date.
            $hasConflict = Booking::where('event_date', $booking->requested_event_date)
                ->where('status', 'approved')
                ->whereNotIn('package', array_keys(Booking::ROOM_GROUPS))
                ->where('id', '!=', $booking->id)
                ->where('user_id', '!=', $booking->user_id)
                ->exists();

            if ($hasConflict) {
                return back()->withErrors(['error' => 'Cannot approve reschedule: another event is already approved on ' . $booking->requested_event_date->format('F d, Y') . '. Only one event per day is allowed.']);
            }
        }

        $booking->event_date = $booking->requested_event_date;

        // Move the visit to the requested date.
        if ($booking->requested_visit_date) {
            $visit = $booking->visitSchedules->first();
            if ($visit) {
                // Bring back a visit that was removed from the list.
                if ($visit->trashed()) {
                    $visit->restore();
                }
                $visit->update([
                    'visit_date' => $booking->requested_visit_date,
                    'status'     => 'rescheduled',
                ]);
            }
        }

        $booking->reschedule_count += 1;
        $booking->reschedule_status = 'approved';
        $booking->requested_event_date = null;
        $booking->requested_visit_date = null;
        $booking->reschedule_reason = null;
        $booking->save();

        return back()->with('success', 'Reschedule approved. Booking dates updated.');
    }

    // Reject a reschedule request.
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