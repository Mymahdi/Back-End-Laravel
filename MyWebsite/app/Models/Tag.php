<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Define relationship with blogs
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'tags_blogs', 'tag_id', 'blog_id');
    }
}
