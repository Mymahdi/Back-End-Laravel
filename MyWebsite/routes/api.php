<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Middleware\AuthenticateToken;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware([AuthenticateToken::class])->group(function () {
    Route::post('/createBlog', [BlogController::class, 'create']);
    Route::get('/allPosts', [BlogController::class, 'getAllPosts']);
    Route::get('/userPosts', [BlogController::class, 'getUserBlogs']);
    Route::post('/editBlog/{id}', [BlogController::class, 'edit']);
    Route::delete('/deletePost/{id}', [BlogController::class, 'deletePost']);
});