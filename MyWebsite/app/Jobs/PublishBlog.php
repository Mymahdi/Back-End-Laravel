<?php
namespace App\Jobs;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\PseudoTypes\True_;

class PublishBlog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $blogId;

    public function __construct(int $blogId)
    {
        $this->blogId = $blogId;
    }

    public function handle()
    {
        // Find the blog post by ID
        $blog = Blog::find($this->blogId);

        if ($blog) {
            // Update the blog's published status and publication date
            $blog->is_published = intval(1);
            // $blog->published_at = now();
            $blog->save();
        }
    }
}
