<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateBlogRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditBlogRequest;

class BlogController extends Controller
{

public function create(CreateBlogRequest $request): JsonResponse
{
    $user = Auth::user();
    $publishAt = $request->publish_at ?? now();
    $blog = Blog::create([
        'title' => $request->title,
        'body' => $request->body,
        'author_name' => $user->first_name . ' ' . $user->last_name,
        'user_id' => $user->id,
        'publish_at' => $publishAt,
        'is_published' => ($publishAt == now()) ? true : false,
    ]);

    $UniqueTagsArray = array_unique(array: $request->tags);
    Tag::attachTagsToBlog($blog, $UniqueTagsArray);

    return response()->json(['message' => 'Blog created successfully'], 201);
}

    
    public function edit(EditBlogRequest $request, int $id)
    {
        $user = Auth::user();
        $blogFound = Blog::where('id', $id)->where('user_id', $user->id)->first();
        if (!$blogFound) {
            return response()->json(['error' => 'Blog not found or you do not have permission to edit this blog.'], 404);
        }
        $blog = $blogFound;
        $blog->title = $request->title ?? $blog->title;
        $blog->body = $request->body ?? $blog->body;
        $blog->publish_at = $request->publish_at ?? $blog->publish_at;
        $blog->save();
        $blog->tags()->sync([]); 
        if ($request->has('tags')) {
            $UniqueTagsArray = array_unique(array: $request->tags);
            Tag::attachTagsToBlog($blog, $UniqueTagsArray);
        }

        return response()->json(['message' => 'Blog edited successfully.'], 200);
    }
    

    public function deletePost(Request $request, int $id): JsonResponse
    {
        $blogFound = Blog::where('id', $id)
        ->where('user_id', $request->user_id)->first();
        if (!$blogFound) {
            return response()->json(['error' => 'Blog not found or you do not have permission to edit this blog.'], 404);
        }
        $blogFound->delete();
        return response()->json(['message' => 'Post deleted successfully.']);
    }

    public function showAllBlogs(): JsonResponse
    {
        $postsData = Blog::getAllBlogsWithFormattedData();
        if ($postsData->isEmpty()) {
            return response()->json(['message' => 'No posts found.'], 404);
        }

        return response()->json($postsData, 200);
    }


    public function getUserBlogs(Request $request): JsonResponse
    {
        $userBlogs = Blog::where('user_id', $request->user_id)->select('title','body','author_name')->get();
        
        if ($userBlogs->isEmpty()) {
            return response()->json(['message' => ' This user has no blogs.'], 404);
        }

        return response()->json($userBlogs);
    }
}
