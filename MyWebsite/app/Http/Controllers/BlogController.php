<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(\App\Http\Middleware\AuthenticateToken::class);
    // }

    public function create(Request $request)
    {

        
        // $token = $request->header('Authorization');
    //     $token = $request->header('Authorization');
    //     // return response()->json($token); 
        
    // if (!$token) {
    //     return response()->json(['error' => 'Token not provided.'], 401);
    // }
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
            // 'user_id' =>"5",
        ]);
        
        if ($blog) {
            return response()->json(['message' => 'Blog created successfully'], 201);
        } else {
            return response()->json(['error' => 'Failed to create blog'], 500);
        }
    }
}
