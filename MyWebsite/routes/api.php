<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;



// Public routes

// Routes that require Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('comment-blog/{id}', [CommentController::class, 'addCommentToBlog']);
    Route::post('/create-blog', [BlogController::class, 'create']);
    Route::put('/edit-blog/{id}', [BlogController::class, 'edit']);
    Route::delete('/delete-blog/{id}', [BlogController::class, 'deletePost']);
    Route::get('/show-all-posts', [BlogController::class, 'showAllBlogs']);
    Route::get('/show-user-posts', [BlogController::class, 'showUserBlogs']);
    
    Route::post('/like-blog/{id}', [BlogController::class, 'likeBlog']);
    Route::delete('/unlike-blog/{id}', [BlogController::class, 'unlikeBlog']);
    // Route::get('/export-blogs', [AdminController::class, 'exportBlogs']);
    
    Route::delete('/logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::delete('logout', [AuthController::class, 'logout']);
//     // Other routes that require authentication can go here
// });
// Route::middleware('auth:api')->post('/create-blog', [BlogController::class, 'store']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route::middleware([AuthenticateToken::class])->group(function () {

    

//     Route::get('/blog-likers/{id}', [BlogController::class, 'getLikers']);
//     Route::get('/tags-list', [BlogController::class, 'getTagsList']);

//     Route::get('/serach-blog', [BlogController::class, 'searchBlogs']);
    
// });
Route::fallback(function () {
    return response()->json(['message' => 'Resource not found.'], 404);
});