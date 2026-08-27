<?php

namespace App\Http\Controllers;

use \App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('name', $fields['name'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong credentials'
            ], 401);
        }

        if ($user->active == false) {
            return response([
                'message' => 'User not active'
            ], 401);
        }
        
        $token = $user->createToken('token', [$user->role])->plainTextToken;

        $cookie = cookie(
        'access_token',
        $token,
        60, // minutes
        '/',
        null,
        false, // secure: true in production
        true,  // HttpOnly
        false,
        'lax'
        );

        return response()->json(['token' => $token, 'role' => $user->role, 'message' => 'Logged in'])->cookie($cookie);
    }

    public function logout(Request $request) {
        
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'])
            ->withoutCookie('access_token', '/', null, false, true, false, 'lax');
    }
}
