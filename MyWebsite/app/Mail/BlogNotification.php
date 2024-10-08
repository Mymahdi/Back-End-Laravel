<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
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
        $blogLink = route('blog.show', ['id' => $this->blog->id]);

        // Log the blog link value
        Log::info('Blog link for email: ' . $blogLink);
       return new Content(
            view: 'email.emailContent',
            with: [
                'authorFirstName' => $this->author->first_name,
                'authorLastName' => $this->author->last_name,
                'blogTitle' => $this->blog->title,
                'authorEmail' => $this->author->email,
                'blogLink' => $this->blogLink = url('/api/show-Notified-Link/' . $this->blog->id),
                // 'blogLink' => route('blog.show', ['id' => $this->blog->id]),

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
