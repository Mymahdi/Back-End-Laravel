<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class DailyBlogLimit
{
    public function handle($request, Closure $next): mixed
    {
        $key = 'publish_' . $request->user()->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['message' => 'Too many publish attempts. Please try again later.'], 429);
        }

        RateLimiter::hit($key);

        return $next($request);
    
    }
}
