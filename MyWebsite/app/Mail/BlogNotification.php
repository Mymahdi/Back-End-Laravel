<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BlogNotification extends Mailable
{
    use Queueable, SerializesModels;

    // public $author;
    public $blogLink;

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

    public function content(): Content
    {
        return new Content(
            view: 'email.blog_published',
            with: [
                'authorFirstName' => $this->author->first_name,
                'authorLastName' => $this->author->last_name,
                'blogTitle' => $this->blog->title,
                'authorEmail' => $this->author->email,
                'blogLink' => $this->blogLink = url('/blogs/' . $this->blog->id),
            ]
        );
    }

    /**
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
