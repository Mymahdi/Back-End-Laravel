<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    public function likeable()
    {
        return $this->morphTo();
    }


    public $timestamps = false;
   
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    public static function like($likeableType, $likeableId): array
    {
        $likeExists = self::where('likeable_id', $likeableId)
            ->where('likeable_type', $likeableType)
            ->where('user_id', Auth::id())
            ->exists();
            if ($likeExists == true) {
                return ['message' => 'You have already liked this.'];
            }
            
        return self::create([
            'user_id' => Auth::id(),
            'likeable_id' => $likeableId,
            'likeable_type' => $likeableType,
        ]);
    }

    public static function unlike($likeableType, $likeableId): array
    {
        $likedBlog = self::where('likeable_id', $likeableId)
            ->where('likeable_type', $likeableType)
            ->where('user_id', Auth::id())
            ->first();
            if ($likedBlog == false) {
                return ['message' => 'You have not liked this post.'];
            }
            
            $likedBlog->delete();
            return ['message' => 'Successfully unliked.'];
    }

}
