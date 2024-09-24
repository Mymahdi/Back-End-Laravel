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
    Route::post('/create-blog', [BlogController::class, 'create']);

    Route::get('/all-posts', [BlogController::class, 'getAllPosts']);
    Route::get('/user-posts', [BlogController::class, 'getUserBlogs']);
    
    Route::put('/edit-blog/{id}', [BlogController::class, 'edit']);
    Route::delete('/delete-blog/{id}', [BlogController::class, 'deletePost']);
    
    Route::get('/blog-likers/{id}', [BlogController::class, 'getLikers']);
    Route::get('/tags-list', [BlogController::class, 'getTagsList']);

    Route::post('/like-blog/{id}', [BlogController::class, 'likeBlog']);
    Route::delete('/unlike-blog/{id}', [BlogController::class, 'unlikeBlog']);
    Route::get('/serach-blog', [BlogController::class, 'searchBlogs']);
    
    Route::delete('/logout', [AuthController::class, 'logout']);
});
Route::fallback(function () {
    return response()->json(['message' => 'Resource not found.'], 404);
});