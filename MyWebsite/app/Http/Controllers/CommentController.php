<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class CommentController extends Controller
{
    public function addCommentToBlog(Request $request, $blogId): JsonResponse
    {
        $request->validate([
            'comment' => 'required|string|max:1000|min:3',
        ]);
        
        $blog = Blog::findOrFail($blogId);
        
        $userId = Auth::id();
        $comment = new Comment([
            'body' => $request->comment,
            'user_id' => $userId,
        ]);
        
        $blog->comments()->save($comment);

        return response()->json(['message' => 'Comment added successfully'], 201);
    }

    public function addCommentReply(Request $request, int $commentId): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:1000|min:3',
        ]);
    
        $user = Auth::user();
        $parentComment = Comment::findOrFail($commentId);
    
        $replyComment = new Comment();
        $replyComment->body = $request->body;
        $replyComment->user_id = $user->id;
        $replyComment->commentable_id = $parentComment->id;
        $replyComment->commentable_type = Comment::class;
        $replyComment->save();
    
        return response()->json(['message' => 'Reply added successfully.', 'comment' => $replyComment], 201);
    }
    

}
