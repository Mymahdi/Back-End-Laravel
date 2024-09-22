<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class AuthenticateToken
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['error' => 'Token not provided.'], 401);
        }
        
        $token = str_replace('Bearer ', '', $token);
        $user = DB::table('users')
        ->join('user_tokens', 'users.id', '=', 'user_tokens.user_id')
        ->where('user_tokens.token', $token)
        ->where('user_tokens.expires_at', '>=', Db::raw('CURRENT_TIMESTAMP'))
        ->select('users.id')
        ->first();
        // return response()->json($request);
        // return response()->json($user);
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized. Invalid token or token expired.'], 401);
        }

        // Add the user ID to the request for further use
        $request->merge(['user_id' => $user->id]);

        return $next($request);
    }
}
