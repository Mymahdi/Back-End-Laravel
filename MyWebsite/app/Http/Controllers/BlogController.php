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

    
public function edit(EditBlogRequest $request, int $id): JsonResponse
{
    $user = Auth::user();
    $blog = Blog::find($id);
    if ($blog == null) {
        return response()->json(['error' => 'Blog not found.'], 404);
    }

    if (!$blog->updateBlog($request->validated(), $user->id)) {
        return response()->json(['error' => 'You do not have permission to edit this blog.'], 403);
    }
    return response()->json(['message' => 'Blog updated successfully.']);
}
 

public function deletePost(Request $request, int $id): JsonResponse
{
    $request->validate([
        'id' => 'integer'
    ]);
    $user = Auth::user();
    $blog = Blog::where('id', $id)
        ->where('user_id', $user->id)
        ->first();

    if ($blog == null) {
        return response()->json(['error' => 'Blog not found or you do not have permission to delete this blog.'], 404);
    }

    $blog->delete();
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
