<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Middleware\AuthenticateToken;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware([AuthenticateToken::class])->group(function () {
    Route::post('/editBlog/{id}', [BlogController::class, 'edit']);
    Route::post('/createBlog', [BlogController::class, 'create']);
});