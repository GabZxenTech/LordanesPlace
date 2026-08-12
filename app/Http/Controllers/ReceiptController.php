<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Download the booking receipt as PDF.
     */
    public function download(Booking $booking)
    {
        // Authorization: Only the owner of the booking or an admin can download
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access to this receipt.');
        }

        $booking->loadMissing('user', 'visitSchedules');

        // The official receipt only exists once an admin has confirmed an
        // actual payment — selecting a payment option at booking time is not
        // proof of payment and never satisfies this.
        if (!$booking->hasConfirmedPayment()) {
            abort(404, 'Receipt not yet available. Payment confirmation is required.');
        }

        // Receipt-sized page (points, 1pt = 1/72in) so the slip fills the sheet
        // instead of sitting on a mostly-blank A4. ~300x494pt ≈ 106x174mm.
        // The optional "Visit Schedule" row needs one extra line of height.
        // Measured empirically against the actual template — if rows are ever
        // added to receipt.blade.php, re-check these with DomPDF's page count
        // rather than guessing, since a miss silently spills to a 2nd page.
        $hasVisitRow = $booking->visitSchedules->where('status', 'confirmed')->isNotEmpty();
        $pageHeight = $hasVisitRow ? 510 : 494;

        $pdf = Pdf::loadView('receipt', compact('booking'))
                 ->setPaper([0, 0, 300, $pageHeight]);

        $filename = 'Receipt-' . ($booking->booking_number ?? $booking->id) . '.pdf';

        return $pdf->download($filename);
    }
}
