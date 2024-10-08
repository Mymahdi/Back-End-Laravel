<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BlogNotification extends Mailable
{
    use Queueable, SerializesModels;

    // public $author;
    // public $blog;

    /**
     * Create a new message instance.
     */
    public function __construct(private $author,private $blog)
    {
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Blog Published: ' . $this->blog->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.blog_published',
            with: [
                'authorName' => $this->author->first_name,
                'blogTitle' => $this->blog->title,
                'authorEmail' => $this->author->email,
                // 'blogLink' => $this->blogLink,
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
