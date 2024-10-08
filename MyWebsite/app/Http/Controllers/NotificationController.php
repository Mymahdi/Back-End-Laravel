<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUserNotifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->get();
        return response()->json($notifications);
    }
}
