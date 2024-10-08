<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/blog/{id}', [BlogController::class, 'show']);
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
// Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
