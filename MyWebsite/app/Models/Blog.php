<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'author_name',
        'user_id',
        'is_published',
    ];
    
    
    protected static function boot(): void
    {
        parent::boot();
        
        static::deleting(function ($blog) {
            $blog->comments()->delete();
        });

        static::creating(function ($model): void {
            $model->created_at = now();
            $model->updated_at = null;
        });
    }
    
    public static function getAllBlogsWithFormattedData(int $perPage = 10): LengthAwarePaginator
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
            ->paginate($perPage);

        $authenticatedUserId = Auth::id();

        $allBlogs->getCollection()->transform(function (Blog $post) use ($authenticatedUserId): array {
            return [
                'title' => $post->title,
                'body' => $post->body,
                'author_name' => $post->author->first_name . ' ' . $post->author->last_name,
                'like_status' => $post->likes->contains('user_id', $authenticatedUserId) ? "Liked" : "Not Liked",  
                'likes_count' => $post->likes()->count(),
                'comments' => $post->comments->map(function ($comment) use ($authenticatedUserId) {
                    return [
                        'comment_body' => $comment->body,
                        'user_name' => $comment->user->first_name . ' ' . $comment->user->last_name, 
                        'like_status' => $comment->likes->contains('user_id', $authenticatedUserId) ? "Liked" : "Not Liked",
                        'likes_count' => $comment->likes()->count(),
                    ];
                }),
            ];
        });

        return $allBlogs;
    }

    public static function userBlogs(): Collection
    {
        $authenticatedUserId = Auth::id();
        $userBlogs = self::with('comments')
        ->where('user_id', Auth::id())
        ->select('id','title', 'body', 'author_name')
        ->get();
        return $userBlogs->map(function (Blog $post) use ($authenticatedUserId): array {
            return [
                'title' => $post->title,
                'body' => $post->body,
                'author_name' => $post->first_name . ' ' . $post->last_name,
                'like_status' => $post->likes->contains('user_id', $authenticatedUserId)?"Liked":"Not Liked",  
                'likes_count' => $post->likes()->count(),
                'comments' => $post->comments->map(function ($comment) use ($authenticatedUserId) {
                    return [
                        'comment_body' => $comment->body,
                        'user_name' => $comment->user->first_name . ' ' . $comment->user->last_name, 
                        'like_status' => $comment->likes->contains('user_id', $authenticatedUserId)?"Liked":"Not Liked",
                        'likes_count' => $comment->likes()->count(),
                    ];
                }),
            ];
        });
    }



    public static function search($title = null, $body = null, $authorName = null): Collection
    {
        return self::query()
        ->where('is_published', 1)
        ->when($title, function ($query, $title) {
            $query->orWhere('title', 'like', '%' . $title . '%');
        })
        ->when($body, function ($query, $body) {
            $query->orWhere('body', 'like', '%' . $body . '%');
        })
        ->when($authorName, function ($query, $authorName) {
            $query->orWhere('author_name', 'like', '%' . $authorName . '%');
        })
        ->get(['id', 'title', 'body', 'author_name']);
    }

    public function getLikersInfo(): mixed
    {
        return $this->likers()->get(['first_name', 'last_name', 'role']);
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
    public function likers()
{
    return $this->hasManyThrough(User::class, Like::class, 'likeable_id', 'id', 'id', 'user_id')
                ->where('likeable_type', Blog::class);
}
}
