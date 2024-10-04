<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'author_name',
        'user_id',
        'publish_at',
        'is_published',
    ];
    
    
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            $model->created_at = now();
            $model->updated_at = null;
        });
    }

    public static function getAllBlogsWithFormattedData()
    {
        $allBlogs = self::with([
            'author',
            'comments' => function ($query) {
                $query->select('id', 'body', 'commentable_id', 'user_id', 'created_at')
                      ->withCount('likes');
            }
        ])
        ->withCount('likes')
        ->select('id', 'title', 'body', 'user_id')
        ->get();
        $authenticatedUserId = Auth::id();
        return $allBlogs->map(function (Blog $post) use ($authenticatedUserId): array {
            return [
                'title' => $post->title,
                'body' => $post->body,
                'author_name' => $post->author->first_name . ' ' . $post->author->last_name,
                'user_id' => $post->user_id,
                'like_status' => $post->likes->contains('user_id', $authenticatedUserId)?"Liked":"Not Liked",  
                'likes_count' => $post->likes_count ?? 0, 
                'comments' => $post->comments->map(function ($comment) use ($authenticatedUserId) {
                    return [
                        'comment_body' => $comment->body,
                        'user_name' => $comment->user->first_name . ' ' . $comment->user->last_name, 
                        'like_status' => $comment->likes->contains('user_id', $authenticatedUserId)?"Liked":"Not Liked",
                        'likes_count' => $comment->likes_count ?? 0,
                    ];
                }),
            ];
        });
        
    }


public function updateBlog(array $data, int $userId): bool
{
    if ($this->user_id !== $userId) {
        throw new \Exception('You do not have permission to edit this blog.');
    }
    $this->title = $data['title'] ?? $this->title;
    $this->body = $data['body'] ?? $this->body;
    $this->publish_at = $data['publish_at'] ?? $this->publish_at;
    $this->tags()->sync([]); 
    $UniqueTagsArray = array_unique(array: $data['tags']);
    Tag::attachTagsToBlog($this, $UniqueTagsArray);
    
    return $this->save();
}

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tags_blogs', 'blog_id', 'tag_id');
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
