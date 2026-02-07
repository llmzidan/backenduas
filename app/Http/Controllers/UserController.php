<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // 1. GET ALL DATA USERS
    public function index(Request $request)
    {

        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only!'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => User::all()
        ], 200);
    }

    // 2. ADD USER
    public function store(Request $request)
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
            'role'     => $request->role,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data'    => $user
        ], 201);
    }

    // 3. EDIT USER
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $validator = Validator::make($request->all(), [
    'name'     => 'required|min:5|max:20',
    'email'    => 'required|email|unique:users,email,' . $id,
    'password' => 'nullable|min:6|max:20', // Ganti jadi nullable
    'role'     => 'required|in:admin,member',
]);
if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

// Terus pas mau update, dicek dulu:
$updateData = [
    'name'  => $request->name,
    'email' => $request->email,
    'role'  => $request->role,
];

// Kalau password diisi, baru kita masukin ke array update
if ($request->filled('password')) {
    $updateData['password'] = Hash::make($request->password);
}

$user->update($updateData);




       

        return response()->json([
            'message' => 'User updated successfully',
            'data'    => $user
        ], 200);
    }

    // 4. DELETE USER
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
