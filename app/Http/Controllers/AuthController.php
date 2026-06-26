<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = auth()->login($user);

        return ApiResponse::success([
            'token' => $token,
            'user' => $user
        ], 'User registered successfully.');
    }


    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!$token) {
            return ApiResponse::error('Invalid credentials.', 401);
        }

        return ApiResponse::success([
            'token' => $token,
            'user' => auth()->user()
        ], 'Login successful.');
    }


    public function logout()
    {

        auth()->logout();

        return ApiResponse::success(null, 'Logged out successfully.');
    }
}
