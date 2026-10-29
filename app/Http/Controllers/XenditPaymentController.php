<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\OnlinePaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// Pay Online through Xendit (test mode with an xnd_development_ key).
// The customer picks GCash, Maya, a bank or a card on the Xendit page.
class XenditPaymentController extends Controller
{
    private const INVOICES = 'https://api.xendit.co/v2/invoices';

    private function client()
    {
        return Http::withBasicAuth((string) config('services.xendit.secret_key'), '')
            ->acceptJson()
            ->timeout(20);
    }

    // Creates the Xendit invoice and sends the customer to its payment page.
    public function start()
    {
        if (!config('services.xendit.secret_key')) {
            return $this->unavailable('not-configured');
        }

        $bookings = OnlinePaymentService::pendingBookings();
        if ($bookings->isEmpty()) {
            return $this->backToProfile('nopending');
        }

        $total = round($bookings->sum(fn (Booking $b) => $b->amountToPay()), 2);
        $numbers = $bookings->pluck('booking_number')->implode(', ');

        try {
            $response = $this->client()->post(self::INVOICES, [
                'external_id'          => 'ldreserve-' . $bookings->first()->booking_number . '-' . Str::lower(Str::random(6)),
                'amount'               => $total,
                'currency'             => 'PHP',
                'description'          => 'LDReserve booking ' . $numbers,
                'invoice_duration'     => 1800,
                'customer'             => [
                    'given_names' => Auth::user()->name,
                    'email'       => Auth::user()->email,
                ],
                'success_redirect_url' => route('payment.xendit.return'),
                'failure_redirect_url' => route('payment.xendit.cancel'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Xendit invoice failed: ' . $e->getMessage());
            return $this->unavailable('connection');
        }

        if (!$response->successful() || !$response->json('invoice_url')) {
            Log::warning('Xendit invoice rejected: ' . $response->status() . ' ' . $response->body());
            return $this->unavailable($response->status() . ' ' . ($response->json('error_code') ?? ''));
        }

        session(['xendit_payment' => ['id' => $response->json('id')]]);

        return redirect()->away($response->json('invoice_url'));
    }

    // The customer comes back from Xendit: ask Xendit if it was really paid.
    public function return()
    {
        $payment = session('xendit_payment');

        // Page refreshed after it was already recorded.
        if (!$payment) {
            return session('demo_paid') ? redirect()->route('payment.done') : redirect()->route('profile');
        }

        try {
            $response = $this->client()->get(self::INVOICES . '/' . $payment['id']);
        } catch (\Throwable $e) {
            Log::warning('Xendit status check failed: ' . $e->getMessage());
            return $this->unavailable();
        }

        if (!in_array($response->json('status'), ['PAID', 'SETTLED'], true)) {
            return $this->backToProfile('notpaid', (string) $response->json('status'));
        }

        $bookings = OnlinePaymentService::pendingBookings();
        if ($bookings->isEmpty()) {
            return redirect()->route('profile');
        }

        $channel = strtoupper((string) $response->json('payment_channel')) ?: 'ONLINE';
        $result = OnlinePaymentService::complete($bookings, 'Xendit (test) · ' . $channel . ' · ' . $payment['id']);

        session(['demo_paid' => [
            'first_id' => $result['first_id'],
            'ref'      => strtoupper(substr($payment['id'], -10)),
            'total'    => $result['total'],
        ]]);
        session()->forget(['demo_pay_ids', 'xendit_payment']);

        return redirect()->route('payment.done');
    }

    // "Payment successful" screen; it sends the customer to their profile after a few seconds.
    public function done()
    {
        $paid = session('demo_paid');
        if (!$paid) {
            return redirect()->route('profile');
        }

        return view('payment-success', [
            'ref'        => $paid['ref'],
            'total'      => $paid['total'],
            'profileUrl' => route('profile') . '#booking-' . $paid['first_id'],
        ]);
    }

    // The customer cancelled or the payment failed on the Xendit page.
    public function cancel()
    {
        return $this->backToProfile('cancelled');
    }

    // Xendit's notice of a finished payment. The payment is confirmed when the customer returns, so just acknowledge.
    public function webhook()
    {
        return response()->json(['ok' => true]);
    }

    // $code is a short reason (an HTTP status or error name), never a secret.
    private function unavailable(string $code = '')
    {
        return $this->backToProfile('unavailable', $code);
    }

    // The profile shows the message from the link itself (pay=...&code=...), so it never gets lost.
    private function backToProfile(string $kind, string $code = '')
    {
        $ids = session('demo_pay_ids', []);
        session()->forget(['demo_pay_ids', 'xendit_payment']);

        $query = ['pay' => $kind] + ($code !== '' ? ['code' => trim($code)] : []);

        return redirect(route('profile', $query) . (!empty($ids) ? '#booking-' . $ids[0] : ''));
    }
}
