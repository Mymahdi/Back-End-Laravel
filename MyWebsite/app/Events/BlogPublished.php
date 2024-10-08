<?php

namespace App\Events;

use App\Models\Blog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BlogPublished
{
    use Dispatchable, SerializesModels;

    public $blog;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Blog $blog
     */
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }
}
