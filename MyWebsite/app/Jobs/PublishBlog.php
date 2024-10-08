<?php
namespace App\Jobs;

use App\Events\NotifyPublishedBlog;
use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class PublishBlog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $blogId;
    public $blog;
    /**
     *
     * @param int $blogId
     */
    public function __construct(int $blogId,)
    {
        $this->blog = Blog::findOrFail($blogId);
    }

    /**
     *
     * @return void
     */
    public function handle(): void
    {
        $this->blog->is_published = true;
        $this->blog->save();
        Log::info('blog dispached succesfully', [
            'blog_id' => $this->blog->id,
            'blog_title' => $this->blog->title,
        ]);
        NotifyPublishedBlog::dispatch($this->blog);
    }
}

