<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
