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
        'num_likes',
        'num_tags',
    ];
    
    protected static function boot()
    {
        parent::boot();

        // Set updated_at to null on creating event
        static::creating(function ($model) {
            $model->created_at = now(); // Automatically set created_at
            $model->updated_at = null;  // Explicitly set updated_at to null
        });
    }
    // Define relationship with tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tags_blogs', 'blog_id', 'tag_id');
    }

    // Define relationship with likes
    public function likes()
    {
        return $this->hasMany(Like::class, 'blog_id');
    }
}
