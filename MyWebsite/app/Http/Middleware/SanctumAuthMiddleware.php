<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanctumAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // return "sanctum setting";
        // Check if the user is authenticated using Sanctum
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized Sanctum method'], 401);
        }

        return $next($request);
    }
}
