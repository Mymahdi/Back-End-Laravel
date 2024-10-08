<?php
namespace App\Jobs;

use App\Events\NoitfyPublishedBlog;
use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class PublishBlog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $blogId;

    /**
     *
     * @param int $blogId
     */
    public function __construct(int $blogId,)
    {
        $this->blogId = $blogId;
    }

    /**
     *
     * @return void
     */
    public function handle(): void
    {
        $blog = Blog::findOrFail($this->blogId);
        $blog->is_published = true;
        $blog->save();
        NoitfyPublishedBlog::dispatch($blog);
    }
}

