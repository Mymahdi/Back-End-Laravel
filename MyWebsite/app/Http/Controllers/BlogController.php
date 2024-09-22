<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\AuthenticateToken;

class BlogController extends Controller
{

    // public function __construct()
    // {
    //     // $this->middleware(\App\Http\Middleware\AuthenticateToken::class);
    //     $this->middleware(AuthenticateToken::class);
    // }

    public function create(Request $request)
    {

        $token = $request->header('Authorization');
        
        if (!$token) {
        return response()->json(['error' => 'Token not provided.'], 401);
    }
        $token = str_replace('Bearer ', '', $token);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'authorName' => 'required|string|max:255',
        ]);
        $blog = DB::table('blogs')->insert([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'author_name' => $validated['authorName'],
            'user_id' =>  $request->user_id,
        ]);
        
        if ($blog) {
            return response()->json(['message' => 'Blog created successfully'], 201);
        } else {
            return response()->json(['error' => 'Failed to create blog'], 500);
        }
    }


    public function edit(Request $request, $id)
{
    $request->validate([
        'body' => 'required|string|max:1000',
        'authorName' => 'required|string|max:255',
        'title' => 'required|string|max:255',
    ]);
    
    $token = $request->header('Authorization');
    if ($token && str_starts_with($token, 'Bearer ')) {
        $token = str_replace('Bearer ', '', $token);
        
        $userToken = DB::table('user_tokens')->where('token', $token)->first();
        if (!$userToken || now()->greaterThan($userToken->expires_at)) {
            return response()->json(['error' => 'Invalid or expired token.'], 401);
        }
        
        $blog = DB::table('blogs')->where('id', $id)->where('user_id', $userToken->user_id)->first();
        if (!$blog) {
            return response()->json(['error' => 'Blog not found or you do not have permission to edit this blog.'], 404);
        }

        DB::table('blogs')->where('id', $id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'author_name' => $request->authorName,
            'last_update' => DB::raw('CURRENT_TIMESTAMP'),
        ]);

        return response()->json(['message' => 'Blog edited successfully.'], 200);
    }

    return response()->json(['error' => 'Token not provided try again.'], 401);
}

}
