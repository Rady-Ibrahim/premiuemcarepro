<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnreadChatNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $unreadCount;

    /**
     * Create a new message instance.
     */
    public function __construct($unreadCount)
    {
        $this->unreadCount = $unreadCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تنبيه: رسائل غير مقروءة في الشات',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.unread-chat-notification',
            with: [
                'unreadCount' => $this->unreadCount,
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
