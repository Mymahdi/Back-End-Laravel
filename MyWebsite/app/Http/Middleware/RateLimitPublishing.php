<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitPublishing
{
    public function handle(Request $request, Closure $next): mixed
    {
        $key = 'publish_' . $request->user()->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['message' => 'Too many publish attempts. Please try again later.'], 429);
        }

        RateLimiter::hit($key);

        return $next($request);
    }
}
