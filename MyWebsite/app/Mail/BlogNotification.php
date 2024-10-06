<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BlogNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $authorName;
    public $blogTitle;
    public $authorEmail;
    public $blogLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($authorName, $blogTitle, $authorEmail, $blogLink)
    {
        $this->authorName = $authorName;
        $this->blogTitle = $blogTitle;
        $this->authorEmail = $authorEmail;
        $this->blogLink = $blogLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Blog Published: ' . $this->blogTitle)
                    ->view('emails.blog_published')
                    ->with([
                        'authorName' => $this->authorName,
                        'blogTitle' => $this->blogTitle,
                        'authorEmail' => $this->authorEmail,
                        'blogLink' => $this->blogLink,
                    ]);
    }
}
