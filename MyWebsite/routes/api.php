<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Middleware\AuthenticateToken;
use App\Http\Controllers\AdminController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/export-blogs', [AdminController::class, 'exportBlogs']);

Route::middleware([AuthenticateToken::class])->group(function () {
    Route::post('/createBlog', [BlogController::class, 'create']);

    Route::get('/allPosts', [BlogController::class, 'getAllPosts']);
    Route::get('/userPosts', [BlogController::class, 'getUserBlogs']);
    
    Route::put('/editBlog/{id}', [BlogController::class, 'edit']);
    Route::delete('/deletePost/{id}', [BlogController::class, 'deletePost']);
    
    Route::get('/blog-likers/{id}', [BlogController::class, 'getLikers']);
    Route::post('/likeBlog/{id}', [BlogController::class, 'likeBlog']);
    Route::delete('/unlikeBlog/{id}', [BlogController::class, 'unlikeBlog']);
    Route::get('/serachBlog', [BlogController::class, 'searchBlogs']);
    
    Route::delete('/logout', [AuthController::class, 'logout']);
});
Route::fallback(function () {
    return response()->json(['message' => 'Resource not found.'], 404);
});