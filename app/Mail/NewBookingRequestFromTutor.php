<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingRequestFromTutor extends Mailable
{
    use Queueable, SerializesModels;
    private $fullname;
    private $role;
    private $booking;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $booking)
    {
        $this->fullname = $user->firstname . " " . $user->lastname;
        $this->role = $user->role;
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Request From Tutor 👏',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking.new_request_from_tutor',
            with: [
                'fullname' => $this->fullname,
                'role' => $this->role,
                'booking' => $this->booking,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
