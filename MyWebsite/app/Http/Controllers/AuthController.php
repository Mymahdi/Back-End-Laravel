<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $vlidated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        UserModel::create($vlidated);

        return response()->json([
            'message' => 'User registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        
        $user = DB::table('users')->where('email', $request->email)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            
            $token = bin2hex(random_bytes(10));
            DB::table('user_tokens')->insert([
                'user_id'    => $user->id,
                'token'      => $token, 
                'expires_at' => DB::raw('CURRENT_TIMESTAMP + INTERVAL 10 MINUTE'),
            ]);
            
            return response()->json([
                'message' => 'Login successful',
                'token'   => $token,
            ], 200);
        }

        return response()->json(['error' => 'Invalid username or password!'], 401);
    }

    // public function logout(Request $request)
    // {
    //     $token = $request->header('Authorization');
         
    //     return $token;
    //     if (!$token) {
    //         return response()->json(['error' => 'Token not provided.'], 401);
    //     }
    
    //     // Remove the 'Bearer ' prefix
    //     $token = str_replace('Bearer ', '', $token);
    
    //     // Delete the token from the database
    //     $deleted = DB::table('user_tokens')->where('token', $token)->delete();
    
    //     // Check if the token was successfully deleted
    //     if ($deleted) {
    //         return response()->json(['message' => 'Logged out successfully.']);
    //     } else {
    //         return response()->json(['error' => 'Token not found.'], 404);
    //     }
    // }
    

    
}
