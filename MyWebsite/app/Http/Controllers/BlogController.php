<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\EditBlogRequest;
use Hamcrest\Arrays\IsArray;

use function PHPUnit\Framework\isNull;

class BlogController extends Controller
{

    public function store(Request $request): Request
    {
        return $request;
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
    }
}


public function create(CreateBlogRequest $request): JsonResponse
{
    $user = User::find($request->user_id);
    $blog = Blog::create([
        'title' => $request->title,
        'body' => $request->body,
        'author_name' => $user->first_name . ' ' . $user->last_name,
        'user_id' => $request->user_id,
        'publish_at' => $request->publish_at ?? now(),
    ]);

    $UniqueTagsArray = array_unique($request->tags);
    Tag::attachTagsToBlog($blog, $UniqueTagsArray);

    return response()->json(['message' => 'Blog created successfully'], 201);
}

    
    public function edit(EditBlogRequest $request, $id)
    {
        $blogFound = Blog::where('id', $id)->where('user_id', $request->user_id)->first();
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
            $newTags = array_unique(array: $request->tags);
            
            foreach ($newTags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $blog->tags()->attach($tag->id);
            }
        }

        return response()->json(['message' => 'Blog edited successfully.'], 200);
    }
    

    public function deletePost(Request $request, $id)
    {
        $blog = Blog::where('id', $id)->where('user_id', $request->user_id)->first();
        if (!$blog) {
            return response()->json(['error' => 'Blog not found or you do not have permission to edit this blog.'], 404);
        }

        $blog->delete();
        return response()->json(['message' => 'Post deleted successfully.']);
    }

    public function getAllPosts()
{
    $posts = Blog::with('comments')->select('title', 'body', 'user_id')->get();
    // return $posts;
    if ($posts->isEmpty()) {
        return response()->json(data: ['message' => 'No posts found.'], status: 404);
    }

    $postsData = $posts->map(function (Blog $post): array {
        return [
            'title' => $post->title,
            'body' => $post->body,
            'author_name' =>$post->author->first_name . ' ' . $post->author->last_name,
            'comments' => $post->comments->map(function ($comment): array {
                return [
                    'user_id' => $comment->user_id,
                    'comment_body' => $comment->body,
                    'created_at' => $comment->created_at,
                    'likes_count' => $comment->likes->count(),
                ];
            }),
        ];
    });

    return response()->json($postsData);
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
