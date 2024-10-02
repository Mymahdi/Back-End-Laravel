<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'tags_blogs');
    }

    public static function attachTagsToBlog($blog, array $tagsArray): void
    {
        foreach ($tagsArray as $tagName) {
            $tag = self::firstOrCreate(['name' => $tagName]);

            $blog->tags()->attach($tag->id);
        }
    }
}
