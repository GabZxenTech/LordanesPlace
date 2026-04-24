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

        // Load thermal-style or professional receipt view
        $pdf = Pdf::loadView('receipt', compact('booking'))
                 ->setPaper('a4', 'portrait');

        $filename = 'Receipt-' . ($booking->booking_number ?? $booking->id) . '.pdf';

        return $pdf->download($filename);
    }
}
