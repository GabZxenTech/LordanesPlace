<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\OnlinePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Simulated GCash payment, used when no Xendit key is set. No real money moves and no gateway is called.
class DemoPaymentController extends Controller
{
    public function show()
    {
        $bookings = OnlinePaymentService::pendingBookings();
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

        $bookings = OnlinePaymentService::pendingBookings();
        if ($bookings->isEmpty()) {
            return redirect()->route('booking');
        }

        $ref = 'GC' . strtoupper(Str::random(10));
        $note = 'GCash (demo) · Ref ' . $ref . ' · ***' . substr($request->mobile, -4);

        $result = OnlinePaymentService::complete($bookings, $note);

        session(['demo_paid' => [
            'first_id' => $result['first_id'],
            'ref'      => $ref,
            'total'    => $result['total'],
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
        session()->forget(['demo_pay_ids', 'demo_pay_method', 'xendit_payment', 'wallet_mobile']);

        return redirect(route('profile') . (!empty($ids) ? '#booking-' . $ids[0] : ''))
            ->with('success', 'Payment cancelled. Your booking is saved and still unpaid.');
    }
}
