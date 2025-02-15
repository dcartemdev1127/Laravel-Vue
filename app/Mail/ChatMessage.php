<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChatMessage extends Mailable
{
    use Queueable, SerializesModels;
    private $fullname;
    private $user;
    private $messages = [];

    /**
     * Create a new message instance.
     */
    public function __construct($fullname, $user, $messages)
    {
        $this->fullname = $fullname;
        $this->user = $user;
        $this->messages = $messages;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Message',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.chat.new_message',
            with:[
                'fullname' => $this->fullname,
                'user' => $this->user,
                'messages' => $this->messages
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
