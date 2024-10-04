<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            return $allBlogs->map(function (Blog $post): array {
                return [
                    'title' => $post->title,
                    'body' => $post->body,
                    'author_name' => $post->author->first_name . ' ' . $post->author->last_name,
                    'likes_count' => $post->likes_count ?? 0,
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'comment_body' => $comment->body,
                            'user_name' => $comment->user->first_name . ' ' . $comment->user->last_name,
                            'likes_count' => $comment->likes_count ?? 0,
                        ];
                    }),
                ];
            });
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
