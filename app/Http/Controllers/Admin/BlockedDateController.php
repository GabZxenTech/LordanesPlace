<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Booking;
use App\Services\BookingStatusService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlockedDateController extends Controller
{
    // Show all bookings + blocked dates in admin
    public function index()
    {
        // 'payments' is eager-loaded because the view calls amountPaid()/
        // remainingBalance()/hasConfirmedPayment() several times per row —
        // without this each of those calls re-queries the DB per booking.
        $bookings = Booking::with(['user', 'payments'])->orderBy('event_date', 'asc')->get();
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
        $booking = Booking::findOrFail($id);

        // One Event Per Day: check if another approved booking exists on this date
        $hasConflict = Booking::where('event_date', $booking->event_date)
            ->where('status', 'approved')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($hasConflict) {
            return back()->withErrors(['error' => 'Cannot approve: another event is already approved on ' . $booking->event_date->format('F d, Y') . '. Only one event per day is allowed.']);
        }

        return $this->applyStatus($booking, Booking::STATUS_APPROVED, 'Booking approved.');
    }

    // Reject booking
    public function rejectBooking($id)
    {
        return $this->applyStatus(Booking::findOrFail($id), Booking::STATUS_REJECTED, 'Booking rejected.');
    }

    // Cancel booking (Admin action)
    public function cancelBooking($id)
    {
        return $this->applyStatus(Booking::findOrFail($id), Booking::STATUS_CANCELLED, 'Booking cancelled.');
    }

    // Mark booking as ongoing (Admin action)
    public function markOngoing($id)
    {
        return $this->applyStatus(Booking::findOrFail($id), Booking::STATUS_ONGOING, 'Booking marked as ongoing.');
    }

    // Mark booking as completed (Admin action)
    public function markCompleted($id)
    {
        return $this->applyStatus(Booking::findOrFail($id), Booking::STATUS_COMPLETED, 'Booking marked as completed.');
    }

    /**
     * Generic status update, e.g. from a status <select> on the booking
     * management page. Validated against the workflow like every other path.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(Booking::STATUSES)],
        ]);

        $booking = Booking::findOrFail($id);

        return $this->applyStatus($booking, $request->status, 'Booking status updated to ' . $request->status . '.');
    }

    /**
     * Shared guard + notification path for every admin-driven status change.
     */
    private function applyStatus(Booking $booking, string $status, string $successMessage)
    {
        if (!BookingStatusService::transition($booking, $status)) {
            return back()->withErrors(['error' => BookingStatusService::failureReason($booking, $status)]);
        }

        return back()->with('success', $successMessage);
    }

    /**
     * Record an admin-confirmed payment (the initial downpayment, a full
     * payment, or a later additional payment toward a remaining balance).
     * This is the ONLY way payment_status ever changes — selecting a
     * payment_option at booking time never marks anything as paid.
     */
    public function recordPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:255',
        ]);

        if ($booking->remainingBalance() <= 0) {
            return back()->withErrors(['amount' => 'This booking is already fully paid — there is no remaining balance to confirm.']);
        }

        if ((float) $request->amount > $booking->remainingBalance() + 0.01) {
            return back()->withErrors(['amount' => 'That amount exceeds the remaining balance of ₱' . number_format($booking->remainingBalance(), 2) . '.']);
        }

        $payment = PaymentService::recordPayment($booking, (float) $request->amount, $request->note);

        if (!$payment) {
            return back()->withErrors(['amount' => 'Payment could not be recorded.']);
        }

        return back()->with('success', 'Payment of ₱' . number_format($payment->amount, 2) . ' confirmed for ' . $booking->user->name . '.');
    }

    // Update booking details
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => ['nullable', 'date_format:H:i', function ($attribute, $value, $fail) use ($request) {
                if ($value && $request->filled('start_time') && $value <= $request->start_time) {
                    $fail('The end time must be after the start time.');
                }
            }],
            'guest_count' => 'required|integer|min:1',
            'notes'       => 'nullable|string',
            'package'     => 'required|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // One Event Per Day: if event_date changed, check for conflict
        if ($booking->event_date->format('Y-m-d') !== $request->event_date) {
            $hasConflict = Booking::where('event_date', $request->event_date)
                ->where('status', 'approved')
                ->where('id', '!=', $booking->id)
                ->exists();

            if ($hasConflict) {
                return back()->withErrors(['event_date' => 'Cannot update: another event is already approved on this date. Only one event per day is allowed.'])->withInput();
            }
        }

        // Detect whether the assigned Event Start/End Time or Notes are actually changing,
        // so the customer is only notified when something meaningful changes.
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

        // One Event Per Day: check if the requested date has an existing approved booking
        $hasConflict = Booking::where('event_date', $booking->requested_event_date)
            ->where('status', 'approved')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($hasConflict) {
            return back()->withErrors(['error' => 'Cannot approve reschedule: another event is already approved on ' . $booking->requested_event_date->format('F d, Y') . '. Only one event per day is allowed.']);
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