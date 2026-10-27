<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingStatusService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// DEMO ONLY: a simulated GCash payment. No real money moves and no payment gateway is called.
class DemoPaymentController extends Controller
{
    // The customer's new bookings that are waiting for the demo payment.
    private function pendingBookings()
    {
        return Booking::where('user_id', Auth::id())
            ->whereIn('id', session('demo_pay_ids', []))
            ->orderBy('id')
            ->get()
            ->filter(fn (Booking $b) => $b->remainingBalance() > 0)
            ->values();
    }

    public function show()
    {
        $bookings = $this->pendingBookings();
        if ($bookings->isEmpty()) {
            return redirect()->route('booking');
        }

        $total = $bookings->sum(fn (Booking $b) => $b->amountToPay());

        return view('payment-demo', compact('bookings', 'total'));
    }

    public function pay(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
            'otp'    => 'required|digits:6',
        ], [
            'mobile.regex' => 'Enter an 11-digit mobile number that starts with 09.',
            'otp.digits'   => 'Enter the 6-digit code.',
        ]);

        $bookings = $this->pendingBookings();
        if ($bookings->isEmpty()) {
            return redirect()->route('booking');
        }

        $ref = 'GC' . strtoupper(Str::random(10));
        $note = 'GCash (demo) · Ref ' . $ref . ' · ***' . substr($request->mobile, -4);

        // Record every booking's payment together.
        $payments = DB::transaction(function () use ($bookings, $note) {
            $made = [];
            foreach ($bookings as $booking) {
                $amount = min($booking->amountToPay(), $booking->remainingBalance());
                $payment = PaymentService::recordPayment($booking, $amount, $note);
                if ($payment) {
                    $made[] = [$booking, $payment];
                }
            }
            return $made;
        });

        foreach ($payments as [$booking, $payment]) {
            // Paid online: the booking is ongoing right away.
            BookingStatusService::startAfterOnlinePayment($booking->fresh());
            NotificationService::paymentRecorded($booking->fresh(), $payment);
        }

        session(['demo_paid' => [
            'first_id' => $bookings->first()->id,
            'ref'      => $ref,
            'total'    => collect($payments)->sum(fn ($p) => (float) $p[1]->amount),
        ]]);
        session()->forget('demo_pay_ids');

        return redirect()->route('payment.demo.done');
    }

    // "Payment successful" screen; it sends the customer to their profile after a few seconds.
    public function done()
    {
        $paid = session('demo_paid');
        if (!$paid) {
            return redirect()->route('profile');
        }

        return view('payment-demo-success', [
            'ref'         => $paid['ref'],
            'total'       => $paid['total'],
            'profileUrl'  => route('profile') . '#booking-' . $paid['first_id'],
        ]);
    }

    // Customer cancels the payment; the booking stays saved and unpaid, and they go to their profile.
    public function skip()
    {
        $ids = session('demo_pay_ids', []);
        session()->forget('demo_pay_ids');

        return redirect(route('profile') . (!empty($ids) ? '#booking-' . $ids[0] : ''))
            ->with('success', 'Payment cancelled. Your booking is saved and still unpaid.');
    }
}
