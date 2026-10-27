<?php

namespace App\Mail;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Email to the customer when their booking or payment changes.
class BookingUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    // $details is a plain array so the email shows the numbers as they were when it happened.
    public function __construct(
        public string $userName,
        public string $heading,
        public string $body,
        public array $details,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "LDReserve — {$this->heading} (#{$this->details['number']})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-update',
        );
    }

    // Fully paid: the PDF receipt is attached.
    public function attachments(): array
    {
        $id = $this->details['receiptBookingId'] ?? null;
        if (!$id) {
            return [];
        }

        return [
            Attachment::fromData(function () use ($id) {
                $booking = Booking::with('user', 'visitSchedules')->findOrFail($id);
                $hasVisitRow = $booking->visitSchedules->where('status', 'confirmed')->isNotEmpty();

                return Pdf::loadView('receipt', compact('booking'))
                    ->setPaper([0, 0, 300, $hasVisitRow ? 510 : 494])
                    ->output();
            }, 'Receipt-' . $this->details['number'] . '.pdf')->withMime('application/pdf'),
        ];
    }
}
