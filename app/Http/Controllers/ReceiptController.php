<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    // Downloads the booking receipt as a PDF.
    public function download(Booking $booking)
    {
        // Only the booking owner or an admin.
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access to this receipt.');
        }

        $booking->loadMissing('user', 'visitSchedules');

        // No receipt for cancelled or rejected bookings.
        if ($booking->isCancelledOrRejected()) {
            abort(404, 'Receipt not available — this booking was cancelled or rejected.');
        }

        // A receipt needs at least one payment recorded by the admin.
        if (!$booking->hasConfirmedPayment()) {
            abort(404, 'Receipt not yet available. Payment confirmation is required.');
        }

        // Receipt page size; one extra line when a visit schedule is shown.
        $hasVisitRow = $booking->visitSchedules->where('status', 'confirmed')->isNotEmpty();
        $pageHeight = $hasVisitRow ? 510 : 494;

        $pdf = Pdf::loadView('receipt', compact('booking'))
                 ->setPaper([0, 0, 300, $pageHeight]);

        $filename = 'Receipt-' . ($booking->booking_number ?? $booking->id) . '.pdf';

        return $pdf->download($filename);
    }
}
