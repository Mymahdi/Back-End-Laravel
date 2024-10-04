<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class CommentController extends Controller
{
    public function addCommentToBlog(Request $request, $blogId):JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $blog = Blog::findOrFail($blogId);
        
        $comment = new Comment([
            'body' => $request->comment,
            'user_id' => $request->user_id,
        ]);
        
        $blog->comments()->save($comment);

        return response()->json(['message' => 'Comment added successfully'], 201);
    }
}
