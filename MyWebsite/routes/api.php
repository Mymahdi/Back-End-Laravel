<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Mail\BlogNotification;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


// Routes that require Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('comment-blog/{id}', [CommentController::class, 'addCommentToBlog']);

    Route::post('/create-blog', [BlogController::class, 'create']);
    Route::post('/publish-blog/{id}', [BlogController::class, 'publish'])->middleware('daily.blog.limit');


    Route::get('/user/Notifications', [NotificationController::class, 'getUserNotifications']);

    Route::get('/show-Notified-Link/{id}', [BlogController::class, 'showNotifiedBlog'])->name('blog.show');

    Route::put('/edit-blog/{id}', [BlogController::class, 'edit']);
    Route::delete('/delete-blog/{id}', [BlogController::class, 'deletePost']);
    Route::get('/show-all-posts', [BlogController::class, 'showAllBlogs']);
    Route::get('/show-user-posts', [BlogController::class, 'showUserBlogs']);

    Route::post('/like/{type}/{id}', [BlogController::class, 'likeBlog']);
    Route::delete('/unlike/{type}/{id}', [BlogController::class, 'unlikeBlog']);

    Route::post('/like/{type}/{id}', [BlogController::class, 'likeBlog']);
    Route::delete('/unlike/{type}/{id}', [BlogController::class, 'unlikeBlog']);

    // Route::get('/export-blogs', [AdminController::class, 'exportAllBlogs']);
    Route::get('/exports', [AdminController::class, 'listExports']);
    Route::get('/exports/download/{filename}', [AdminController::class, 'download'])->name('exports.download');
    // Route::get('/export-lastWeek-blogs', [AdminController::class, 'exportWeeklyBlogs'])->middleware('auth', 'role:admin');
    
    Route::get('/export-blogs', [BlogController::class, 'export']);
    Route::get('/export-weekly-blogs', [AdminController::class, 'downloadWeeklyExport']);
    
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