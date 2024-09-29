<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{

    public function store(Request $request)
    {
        return $request;
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
    }
}

    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'authorName' => 'required|string|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        $tagsArray = array_unique(explode(',', $request->tags));
        $blog = Blog::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'author_name' => $validated['authorName'],
            'user_id' => $request->user_id,
            'num_tags' => count(array_unique(explode(',', $request->tags))),
        ]);
        foreach ($tagsArray as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $blog->tags()->attach($tag->id);
        }
        // $this->addTagsToBlog($request->tags, $blog);
        
        return response()->json(['message' => 'Blog created successfully'], 201);
    }
    
    // public static function addTagsToBlog($tags, $blog)
    // {
    //     $tagsArray = array_unique(explode(',', $tags));
    //     foreach ($tagsArray as $tagName) {
    //         $tag = Tag::firstOrCreate(['name' => $tagName]);
    //         $blog->tags()->attach($tag->id);
    //     }
    // }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'authorName' => 'required|string|max:255',
        ]);
        $blog = Blog::where('id', $id)->where('user_id', $request->user_id)->first();
        
        if (!$blog) {
            return response()->json(['error' => 'Blog not found or you do not have permission to edit this blog.'], 404);
        }
        
        $blog->update($request->only(['title', 'body', 'authorName']));
        return response()->json(['message' => 'Blog edited successfully.'], 200);
    }

    public function deletePost($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['error' => 'Blog Id Not Found.'], 404);
        }

        $blog->delete();
        return response()->json(['message' => 'Post deleted successfully.']);
    }

    public function getAllPosts()
    {
        $posts = Blog::select('title', 'body', 'author_name')->get();
        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found.'], 404);
        }

        return response()->json($posts);
    }


    public function getUserBlogs(Request $request)
    {
        $userBlogs = Blog::where('user_id', $request->user_id)->select('title','body','author_name')->get();
        
        if ($userBlogs->isEmpty()) {
            return response()->json(['message' => ' This user has no blogs.'], 404);
        }

        return response()->json($userBlogs);
    }

    public function likeBlog($id, Request $request)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'There is no Blog by this ID'], 400);
        }

        if ($blog->likes()->where('user_id', $request->user_id)->exists()) {
            return response()->json(['message' => 'You have already liked this post'], 400);
        }

        $blog->likes()->create(['user_id' => $request->user_id]);
        $blog->increment('num_likes');
        return response()->json(['message' => 'Post liked successfully!'], 200);
    }

    public function unlikeBlog($id, Request $request)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'There is no Blog by this ID'], 400);
        }

        $like = $blog->likes()->where('user_id', $request->user_id)->first();
        if (!$like) {
            return response()->json(['message' => 'You have not liked this post before'], 400);
        }

        $like->delete();
        $blog->decrement('num_likes');
        return response()->json(['message' => 'Post unliked successfully!'], 200);
    }
}
