<?php
namespace App\Jobs;

use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class PublishBlog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $blogId;
    protected $tags;

    /**
     *
     * @param int $blogId
     * @param array $tags
     */
    public function __construct(int $blogId, array $tags)
    {
        $this->blogId = $blogId;
        $this->tags = $tags;
    }

    /**
     *
     * @return void
     */
    public function handle(): void
    {
        $blog = Blog::find($this->blogId);

        if ($blog) {
            Tag::attachTagsToBlog($blog, $this->tags);
            $blog->is_published = true;
            $blog->save();
        }
    }
}

