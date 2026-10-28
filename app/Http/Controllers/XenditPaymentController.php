<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\OnlinePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// Pay Online through Xendit (test mode with an xnd_development_ key).
// The customer picks GCash, Maya, a bank or a card on the Xendit page.
// After a GCash or Maya payment, our own wallet page asks for the number and code.
class XenditPaymentController extends Controller
{
    private const INVOICES = 'https://api.xendit.co/v2/invoices';

    // Xendit's payment_channel values for the wallets that get the number + code step.
    private const WALLETS = [
        'GCASH'   => ['name' => 'GCash', 'logo' => 'G',    'color' => '#0a7cff', 'dark' => '#0057d9'],
        'PAYMAYA' => ['name' => 'Maya',  'logo' => 'maya', 'color' => '#00a86b', 'dark' => '#00704a'],
    ];

    private function client()
    {
        return Http::withBasicAuth((string) config('services.xendit.secret_key'), '')
            ->acceptJson()
            ->timeout(20);
    }

    // Creates the Xendit invoice and sends the customer to its payment page.
    public function start()
    {
        // No Xendit key: use the built-in simulated page.
        if (config('services.payments.gateway') !== 'xendit') {
            return redirect()->route('payment.demo.show');
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
            return session('demo_paid') ? redirect()->route('payment.demo.done') : redirect()->route('profile');
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

        $channel = strtoupper((string) $response->json('payment_channel'));
        $payment['channel'] = $channel;
        $payment['paid'] = true;
        session(['xendit_payment' => $payment]);

        // GCash / Maya: our wallet page asks for the number and code next.
        if (isset(self::WALLETS[$channel])) {
            return redirect()->route('payment.wallet');
        }

        return $this->finish($payment);
    }

    // The wallet page: number first, then the code.
    public function wallet()
    {
        $payment = session('xendit_payment');
        $wallet = self::WALLETS[$payment['channel'] ?? ''] ?? null;
        $bookings = OnlinePaymentService::pendingBookings();

        if (!$wallet || empty($payment['paid']) || $bookings->isEmpty()) {
            return redirect()->route('profile');
        }

        return view('payment-wallet', [
            'bookings'  => $bookings,
            'total'     => $bookings->sum(fn (Booking $b) => $b->amountToPay()),
            'wallet'    => $wallet['name'],
            'logo'      => $wallet['logo'],
            'color'     => $wallet['color'],
            'colorDark' => $wallet['dark'],
        ]);
    }

    public function walletSubmit(Request $request)
    {
        $payment = session('xendit_payment');
        if (empty($payment['paid'])) {
            return redirect()->route('profile');
        }

        // The +63 is already shown, so the number starts with 9 and has 10 digits.
        $request->validate([
            'mobile' => ['required', 'regex:/^9\d{9}$/'],
            'otp'    => 'required|digits:6',
        ], [
            'mobile.regex' => 'Enter a 10-digit mobile number that starts with 9.',
            'otp.digits'   => 'Enter the 6-digit code.',
        ]);

        return $this->finish($payment, $request->mobile);
    }

    // Records the payment and goes to the success screen.
    private function finish(array $payment, ?string $mobile = null)
    {
        $bookings = OnlinePaymentService::pendingBookings();
        if ($bookings->isEmpty()) {
            return redirect()->route('profile');
        }

        $channel = $payment['channel'] ?: 'ONLINE';
        $result = OnlinePaymentService::complete(
            $bookings,
            'Xendit (test) · ' . $channel . ' · ' . $payment['id'] . ($mobile ? ' · ***' . substr($mobile, -4) : '')
        );

        session(['demo_paid' => [
            'first_id' => $result['first_id'],
            'ref'      => strtoupper(substr($payment['id'], -10)),
            'total'    => $result['total'],
        ]]);
        session()->forget(['demo_pay_ids', 'xendit_payment']);

        return redirect()->route('payment.demo.done');
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
