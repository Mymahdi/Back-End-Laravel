<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class DailyBlogLimit
{
    public function handle($request, Closure $next): mixed
    {
        // $maxDailyBlogs = 5;
        $maxDailyBlogs = 50;
        $user = Auth::user();
        $blogsTodayCount = Blog::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($blogsTodayCount >= $maxDailyBlogs) {
            return response()->json(['error' => "You have used all your $maxDailyBlogs daily blogs."], 403);
        }

        return $next($request);
    }
}
