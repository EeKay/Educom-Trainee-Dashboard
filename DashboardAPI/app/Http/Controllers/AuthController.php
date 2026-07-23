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
            ]);
        }
        
        $token = $user->createToken('token', [$user->role])->plainTextToken;

        return response()->json([
            'token' => $token,
            'Type' => 'Bearer'
        ]);
    }

    public function logout(Request $request) {
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());
        $user = $token->tokenable;
        $user->tokens()->delete();
    }
}
