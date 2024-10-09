<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUserNotifications(): JsonResponse
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->select('id', 'link', 'title', 'blog_id', 'is_read')->get();
        return response()->json($notifications);
    }
}
