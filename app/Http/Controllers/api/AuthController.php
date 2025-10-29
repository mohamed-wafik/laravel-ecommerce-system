<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'data' => null,
                'message' => 'Invalid credentials',
                'status' => 401,
            ], 401);
        }

        $user = User::where('email', $credentials['email'])->first();

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            "token" => $token,
            'data' => $user,
            'message' => 'Login successful',
            'status' => 200,
        ]);
    }

    public function register(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($validation);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "token" => $token,
            'data' => $user,
            'message' => 'Registration successful',
            'status' => 201,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        $cookie = cookie()->forget('token');

        return response()->json([
            'data' => null,
            'message' => 'Logged out successfully',
            'status' => 200,
        ]);
    }

    public function check(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'data' => null,
                'message' => 'Not authenticated',
                'status' => 401,
            ], 401);
        }

        return response()->json([
            'data' => $user,
            'message' => 'Authenticated user',
            'status' => 200,
        ]);
    }

    public function updataPorfile(Request $request) {
        $validation = $request->validate([
            "name" => "nullable|string|min:3|max:50",
            "email" => "nullable|string|email|unique:users,email," . $request->user()->id,
            "avatar" => "nullable|image|mimes:jpg,jpeg,png|max:2048",
        ]);

        $user = $request->user();

        if ($request->hasFile('image')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('image')->store('images', 'public');
            $validated['avatar'] = $path;
        }
        $user->update($validation);
        return response()->json([
            'data' => $user,
            'message' => 'Profile updated successfully',
            'status' => 200,
        ]);
    }

    public function changePassword(Request $request)
    {
        $validation = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validation['current_password'], $user->password)) {
            return response()->json([
                'data' => null,
                'message' => 'Current password is incorrect',
                'status' => 400,
            ], 400);
        }

        $user->password = Hash::make($validation['new_password']);
        $user->save();

        return response()->json([
            'data' => null,
            'message' => 'Password changed successfully',
            'status' => 200,
        ]);
    }

    public function removeAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        return response()->json([
            'data' => $user,
            'message' => 'Avatar removed successfully',
            'status' => 200,
        ]);
    }
}