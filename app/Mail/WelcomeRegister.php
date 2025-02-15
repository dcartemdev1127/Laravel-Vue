<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeRegister extends Mailable
{
    use Queueable, SerializesModels;

    private string $fullname;
    private string $role;
    private string $verifyUrl;
    /**
     * Create a new message instance.
     */
    public function __construct($userData, $verifyUrl)
    {
        $this->fullname = $userData->firstname . " " . $userData->lastname;
        $this->role = $userData->role;
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Register',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'fullname' => $this->fullname,
                'role' => $this->role,
                'verify_url' => $this->verifyUrl
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
