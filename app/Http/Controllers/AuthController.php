<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 1. SIGNUP
    public function signup(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'     => 'required|min:5|max:20',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6|max:20',
        'role'     => 'required|in:admin,member',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'member',
    ]);

    return response()->json([
        'message' => 'User registered successfully as member',
        'data'    => $user
    ], 201);
}
    // 2. SIGNIN
    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login success',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
        'name' => $user->name,
        'role' => $user->role,
        'email'=> $user->email
    ]
        ]);
    }

    // 3. SIGNOUT
    public function signout(Request $request)
    {
        // Menghapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
