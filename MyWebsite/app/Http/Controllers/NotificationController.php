<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUserNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->select('id', 'link', 'title', 'blog_id', 'is_read')->get();
        return response()->json($notifications);
    }
}
