<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingApprovedByParent extends Mailable
{
    use Queueable, SerializesModels;
    private $fullname;
    private $role;
    private $booking;
    private $transaction;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $booking, $transaction = null)
    {
        $this->fullname = $user->firstname . " " . $user->lastname;
        $this->role = $user->role;
        $this->booking = $booking;
        $this->transaction = $transaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Approved By Parent',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking.new_booking_approved_by_parent',
            with: [
                'fullname' => $this->fullname,
                'role' => $this->role,
                'booking' => $this->booking,
                'transaction' => $this->transaction
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
