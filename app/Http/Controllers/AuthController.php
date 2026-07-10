<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{


    /**
     * @OA\Post(
     *      path= "/api/register",
     *      tags= {"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password", "password_confirmation"},
     *              @OA\Property(property="name", type="string", example="Tasneem"),
     *              @OA\Property(property="email", type="string", example="Tasneem@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *              @OA\Property(property="password_confirmation", type="string", example="123456")
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "User registered successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=401,
     *          description= "User failed to registered"
     *      )
     * )
     */



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


    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="Tasneem@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456")
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description="User Loged in successfully"
     *      ),
     * 
     *      @OA\Response(
     *          response=401,
     *          description="User failed to loged in"
     *      )
     * )
     */

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


    /**
     * @OA\Post(
     *      path="/api/logout",
     *      tags={"Authentication"},
     *      security={{"bearerAuth": {}}},
     *      
     *      @OA\Response(
     *          response=200,
     *          description="User Logged out successfully"
     *      ),
     * 
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */

    public function logout()
    {

        auth()->logout();

        return ApiResponse::success(null, 'Logged out successfully.');
    }
}
