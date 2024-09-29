<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanctumAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized Sanctum method'], 401);
        }

        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $request->merge(['user_id' => $user->id]);
        }

        return $next($request);
    }
}
