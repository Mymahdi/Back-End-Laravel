<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogModel
{
    public static function storeBlog($data)
    {
        DB::table('blogs')->insert([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'authorName' => $validated['authorName'],
            'user_id' => auth()->id(),
        ]);
    }


    public static function userLikedBlog($userId,$id)
    {
        return DB::table('likes')->where('user_id', $userId)->where('blog_id', $id)->exists();
    }


    public static function recordLike($userId,$blogId)
    {
        return DB::table('likes')->insert([
            'user_id' => $userId,
            'blog_id' => $blogId,
        ]);
    }
    public static function removeLike($userId,$blogId)
    {
        return DB::table('likes')
        ->where('user_id', $userId)
        ->where('blog_id', $blogId)
        ->delete();
    }

    public static function increaseNumLike($blogId)
    {
        DB::table('blogs')
            ->where('id', $blogId)
            ->increment('num_likes', 1);
    }
    
    public static function decreaseNumLike($blogId)
    {
        DB::table('blogs')
            ->where('id', $blogId)
            ->decrement('num_likes');
    }
    
    public static function findBlogs($titleTerm,$bodyTerm,$authorTerm)
    {
        return DB::table('blogs')
        ->where('title', 'LIKE', '%' . $titleTerm . '%')
        ->orWhere('body', 'LIKE', '%' . $bodyTerm . '%')
        ->orWhere('author_name', 'LIKE', '%' . $authorTerm . '%')
        ->get();
    }

    public static function getAllBlogs()
    {
        return DB::table('blogs')
            ->select('id', 'title', 'body', 'author_name', 'num_likes', 'num_tags','user_id','created_at','last_update')
            ->get();
    }

    public static function tagExists($tagName)
    {
        $tag = DB::table('tags')->where('name', $tagName)->first();
        if ($tag) {
            return $tag->id;
        }
        return false;
    }
    public static function addNewTag($tagName)
    {
        return DB::table('tags')->insertGetId(['name' => $tagName,]);
    }
    
    public static function recordTagBlog($blogId,$tagId)
    {
        DB::table('tags_blogs')->insert([
            'tag_id' => $tagId,
            'blog_id' => $blogId,
        ]);
    }
}
