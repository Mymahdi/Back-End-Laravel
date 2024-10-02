<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
            $model->updated_at = null;
        });
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tags_blogs', 'blog_id', 'tag_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
