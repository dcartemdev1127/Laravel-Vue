<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StpEarnNotification extends Mailable
{
    use Queueable, SerializesModels;
    private string $fullname;
    private string $stp;
    /**
     * Create a new message instance.
     */
    public function __construct($fullname, $stp)
    {
        $this->fullname = $fullname;
        $this->stp = $stp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stp Earn Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.stp_earn',
            with:[
                'fullname' => $this->fullname,
                'stp' => $this->stp,
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
