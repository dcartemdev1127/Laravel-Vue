<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class ErrorMail extends Mailable
{
    use Queueable, SerializesModels;
    private $subject;
    private $logmessage;
    /**
     * Create a new message instance.
     */
    public function __construct($subject, $logmessage)
    {
        $this->subject = $subject;
        $this->logmessage = $logmessage;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from:'info@findasupertutor.com',
            subject: $this->subject,
        );
    }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.error',
            with: ['logmessage' => $this->logmessage]
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