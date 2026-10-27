<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
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
}
