<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use App\Jobs\PublishBlog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateBlogRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditBlogRequest;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{

    public function create(CreateBlogRequest $request): JsonResponse
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'author') {
            return response()->json(['error' => 'Simple users not allowed to create a blog.'], 403);
        }
        $blog = Blog::create([
            'title' => $request->title,
            'body' => $request->body,
            'author_name' => $user->first_name . ' ' . $user->last_name,
            'user_id' => $user->id,
        ]);
        Tag::attachTagsToBlog($blog,  array_unique($request->tags));
        return response()->json(['message' => 'Blog created successfully.'], 201);
    }

    public function publish(Request $request, $blogId): JsonResponse
    {
        $request->validate([
            'publish_at' => 'nullable|date|after_or_equal:now',
        ]);
        $user = Auth::user();
        $blog = Blog::findOrFail($blogId);
        
        if ($blog->user_id !== $user->id) {
            return response()->json(['error' => 'Blog not found or You do not have permission to edit this blog.'], 404);
        }
        
        if ($blog->is_published == true) {
            return response()->json(['error' => 'You cannot schedule a published blog.'], 403);
        }
        
        $this->deletePreviousPublishJob($blogId);
        PublishBlog::dispatch($blog->id)->delay(now()->parse($request->publish_at ?? now()));
        return response()->json(['message' => 'The blog is scheduled successfully.']);
    }

    public function showNotifiedBlog($blogId): Factory|View
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('blog_id', $blogId)
            ->first();
            $notification->update(['is_read' => true]);
            $blog = Blog::findOrFail($blogId);
            return view('showBlog', compact('blog'));
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
        
        if ($blog == null || ($blog->user_id !== $user->id && $user->role !== 'admin') || $user->role !== 'author' && $user->role !== 'admin') {
            return response()->json(['error' => 'Blog not found or You do not have permission to edit this blog.'], 404);
        }
        
        $blog->update([
            'title' => $request->title,
            'body' => $request->body,
            'author_name' => $user->first_name . ' ' . $user->last_name,
        ]);
        $blog->tags()->detach();
        Tag::attachTagsToBlog($blog,  array_unique($request->tags));
        $this->deletePreviousPublishJob($blogId);
        return response()->json(['message' => 'Blog edited successfully.']);
    }


    public function deletePost(Request $request, int $blogId): JsonResponse
    {
        $request->validate([
            'id' => 'integer'
        ]);
    
        $user = Auth::user();
        $blog = Blog::findOrFail($blogId);
        if ($user->role === 'admin' || $blog->user_id === $user->id) {
            $blog->delete();
            return response()->json(['message' => 'Post deleted successfully.'], 200);
        } else {
            return response()->json(['error' => 'You do not have permission to delete this post.'], 403);
        }
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

    public function likeItem(string $type,int $id): JsonResponse
    {
        $modelClass = 'App\\Models\\' . ucfirst($type);
        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Invalid type provided.'], 400);
        }
        
        $item = app($modelClass)->find($id);
        if ($item == null) {
            return response()->json(['error' => "$type not found."], 404);
        }
        $likeResult = Like::like(likeableType: $modelClass, likeableId: $id);

        if (isset($likeResult['message'])) {
            return response()->json($likeResult, 400);
        }

        return response()->json(['message' => "$type liked successfully."]);
    }

    public function unlikeItem(string $type,int $id): JsonResponse
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

    public function searchBlogs(Request $request)
    {
        $title = $request->title;
        $body = $request->input('body');
        $authorName = $request->input('authorName');
        $blogs = Blog::search($title, $body, $authorName);
        return response()->json($blogs);
    }

    public function getLikersInfo($blogId)
    {
        $blog = Blog::findOrFail($blogId);
        $likersInfo = $blog->getLikersInfo();
        return response()->json($likersInfo);
    }

}
