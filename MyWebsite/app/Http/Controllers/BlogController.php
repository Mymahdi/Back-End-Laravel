<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\Like;
use App\Models\Comment;
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

    public function showUserBlogs(): JsonResponse
    {
        $userBlogs = Blog::userBlogs();
        if ($userBlogs->isEmpty()) {
            return response()->json(['message' => 'This user has no blogs.'], 404);
        }
    
        return response()->json($userBlogs);
    }

    public function likeBlog(string $type,int $id): JsonResponse
    {
        $modelClass = 'App\\Models\\' . ucfirst($type);
        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Invalid type provided.'], 400);
        }
        
        $item = app($modelClass)->find($id);
        if ($item == null) {
            return response()->json(['error' => 'Blog not found.'], 404);
        }
        $likeResult = Like::like(likeableType: $modelClass, likeableId: $id);

        if (isset($likeResult['message'])) {
            return response()->json($likeResult, 400);
        }

        return response()->json(['message' => "$type liked successfully."]);
    }

    public function unlikeBlog(string $type,int $id): JsonResponse
{
    $modelClass = 'App\\Models\\' . ucfirst($type);
    if (!class_exists($modelClass)) {
        return response()->json(['error' => 'Invalid type provided.'], 400);
    }
    
    $item = app($modelClass)->find($id);
    if ($item == null) {
        return response()->json(['error' => "$type not found."], 404);
    }

    $unlikeResult = Like::unlike(likeableType: $modelClass, likeableId: $id);

    if (isset($unlikeResult['message'])) {
        return response()->json($unlikeResult, 400);
    }

    return response()->json(['message' => 'Blog unliked successfully.']);
}

}
