<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|min:3|max:255',
                'last_name' => 'required|string|min:3|max:255',
                'email' => 'required|email|min:5|max:255|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                ],
                
            ]);
            
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
            ]);

            $token = $user->createToken('RegisterToken')->plainTextToken;

            return response()->json([
                'message' => 'You Registered successfully.',
                'access_token' => $token,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request): JsonResponse
    {
            
        $request->validate([
            'email' => 'required|email|min:5|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken('LoginToken')->plainTextToken;

            return response()->json([
                'massage' => 'Login Successful',
                'access_token' => $token,
                'role' => $user->role,
            ], 200);
        }

        return response()->json(['message' => 'Invalid Email or Password'], 401);
    }
    public function logout(Request $request): JsonResponse
    {
        if (Auth::guard('sanctum')->check()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        }
        return response()->json(['message' => 'Invalid token or user not authenticated'], 401);
    
    }
        
}
