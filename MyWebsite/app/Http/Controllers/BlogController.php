<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Like;
use Illuminate\Support\Facades\DB;
use App\Jobs\PublishBlog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateBlogRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditBlogRequest;

class BlogController extends Controller
{

// public function create(CreateBlogRequest $request): JsonResponse
// {
//     $user = Auth::user();
//     $blog = Blog::create([
//         'title' => $request->title,
//         'body' => $request->body,
//         'author_name' => $user->first_name . ' ' . $user->last_name,
//         'user_id' => $user->id,
//     ]);
//     $UniqueTagsArray = array_unique(array: $request->tags);
//     PublishBlog::dispatch($blog->id,$UniqueTagsArray)->delay(now()->parse($request->publish_at ?? now()));
//     return response()->json(['message' => 'Blog created successfully'], 201);
// }

public function createBlog(CreateBlogRequest $request)
{
    $user = Auth::user();
    $blog = Blog::create([
        'title' => $request->title,
        'body' => $request->body,
        'author_name' => $user->first_name . ' ' . $user->last_name,
        'user_id' => $user->id,
    ]);

    return response()->json(['message' => 'Blog created successfully.'], 201);
}

public function publishBlog(Request $request, $blogId)
{
    $user = Auth::user();
    $blog = Blog::findOrFail($blogId);

    if ($blog == null||$blog->user_id !== $user->id) {
        return response()->json(['error' => 'Blog not found or You do not have permission to edit this blog.'], 404);
    }
    $UniqueTagsArray = array_unique($request->tags);
    PublishBlog::dispatch($blog->id, $UniqueTagsArray)->delay(now()->parse($request->publish_at ?? now()));

    return response()->json(['message' => 'Blog scheduled for publishing successfully.']);
}

protected function deletePreviousPublishJob(int $blogId): void
{
    $jobs = DB::table('jobs')->get();


    foreach ($jobs as $job) {
        $decodedPayload = json_decode($job->payload, true);
        if (isset($decodedPayload['data']['command'])) {
            $command = unserialize($decodedPayload['data']['command']);
            
            if (property_exists($command, 'blogId') && $command->blogId == $blogId) {
                DB::table('jobs')->where('id', $job->id)->delete();
            }
        }
    }
}
public function edit(EditBlogRequest $request, int $blogId): JsonResponse
{
    $user = Auth::user();
    $blog = Blog::findOrFail($blogId);
    
    
    if ($blog == null||$blog->user_id !== $user->id) {
        return response()->json(['error' => 'Blog not found or You do not have permission to edit this blog.'], 404);
    }

    if ($blog->is_published == 1) {
        return response()->json(['error' => 'You cannot edit a published blog.'], 403);
    }
    $blog->update([
        'title' => $request->title,
        'body' => $request->body,
        'author_name' => $user->first_name . ' ' . $user->last_name,
    ]);

    if ($request->has('publish_at')) {
        
        $this->deletePreviousPublishJob($blogId);
        $publishAt = $request->input('publish_at');
        $UniqueTagsArray = array_unique($request->input('tags', [])); 
        PublishBlog::dispatch($blog->id,$UniqueTagsArray)->delay(now()->parse($publishAt ?? now()));
    }

    return response()->json(['message' => 'Blog edited successfully.']);
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
