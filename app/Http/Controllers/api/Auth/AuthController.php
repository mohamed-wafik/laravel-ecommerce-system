<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\ResourceUser;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends BaseController
{
    /**
     * Constructor with dependency injection (Laravel 12)
     * Middleware is defined in routes/api.php
     */
    public function __construct(
        private readonly CloudinaryService $cloudinaryService
    ) {}

    /**
     * Handle user login
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if (!Auth::attempt($credentials)) {
            return $this->sendError('Invalid credentials', [], 401);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        
        // إنشاء Token جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        // إنشاء Cookie آمن
        $cookie = cookie(
            name: 'auth_token',
            value: $token,
            minutes: 60 * 24 * 7, // 7 أيام
            path: '/',
            domain: null,
            secure: app()->environment('production'),
            httpOnly: true,
            sameSite: 'lax'
        );

        return $this->sendResponse([
            'user' => ResourceUser::make($user),
        ], 'Login successful')->withCookie($cookie);
    }

    /**
     * Handle user registration
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = DB::transaction(function () use ($validated) {
            return User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        });

        // تسجيل الدخول وإنشاء Token
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        // إنشاء Cookie
        $cookie = cookie(
            name: 'auth_token',
            value: $token,
            minutes: 60 * 24 * 7,
            path: '/',
            domain: null,
            secure: app()->environment('production'),
            httpOnly: true,
            sameSite: 'lax'
        );

        return $this->sendResponse([
            'user' => ResourceUser::make($user),
        ], 'Registration successful', 201)->cookie($cookie);
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request): JsonResponse
    {
        // حذف Token الحالي من قاعدة البيانات
        $request->user()->currentAccessToken()->delete();

        // حذف Cookie
        $cookie = Cookie::forget('auth_token');

        return $this->sendResponse([], 'Logout successful')->withCookie($cookie);
    }

    /**
     * Check authentication status
     */
    public function check(Request $request): JsonResponse
    {
        return $this->sendResponse(
            ResourceUser::make($request->user()),
            'User is authenticated'
        );
    }

    /**
     * Refresh token (optional - للحصول على token جديد)
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // حذف Token القديم
        $request->user()->currentAccessToken()->delete();
        
        // إنشاء Token جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        // إنشاء Cookie جديد
        $cookie = cookie(
            name: 'auth_token',
            value: $token,
            minutes: 60 * 24 * 7,
            path: '/',
            domain: null,
            secure: app()->environment('production'),
            httpOnly: true,
            sameSite: 'lax'
        );

        return $this->sendResponse([
            'user' => ResourceUser::make($user),
        ], 'Token refreshed successfully')->cookie($cookie);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'min:3', 'max:50'],
            'email' => ['nullable', 'email', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($request, $user, &$validated) {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $this->handleAvatarUpload($user, $request->file('avatar'), $validated);
            }

            // Update only fields that were provided
            $user->update(array_filter($validated, fn($value) => !is_null($value)));
        });

        return $this->sendResponse(
            ResourceUser::make($user->fresh()),
            'Profile updated successfully'
        );
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return $this->sendError('Current password is incorrect', [], 422);
        }

        // Prevent using same password
        if (Hash::check($validated['new_password'], $user->password)) {
            return $this->sendError('New password must be different from current password', [], 422);
        }

        DB::transaction(function () use ($user, $validated) {
            // تحديث كلمة المرور
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);

            // حذف كل الـ Tokens القديمة (تسجيل خروج من كل الأجهزة)
            $user->tokens()->delete();
        });

        // إنشاء token جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        // إنشاء Cookie جديد
        $cookie = cookie(
            name: 'auth_token',
            value: $token,
            minutes: 60 * 24 * 7,
            path: '/',
            domain: null,
            secure: app()->environment('production'),
            httpOnly: true,
            sameSite: 'lax'
        );

        return $this->sendResponse([], 'Password changed successfully')->cookie($cookie);
    }

    /**
     * Remove user avatar
     */
    public function removeAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->avatar_public_id) {
            return $this->sendError('No avatar to remove', [], 404);
        }

        DB::transaction(function () use ($user) {
            // Delete from Cloudinary
            $deleted = $this->cloudinaryService->deleteFile($user->avatar_public_id);
            
            if (!$deleted) {
                throw new \Exception('Failed to delete avatar from Cloudinary');
            }

            // Clear avatar fields
            $user->update([
                'avatar' => null,
                'avatar_public_id' => null,
            ]);
        });

        return $this->sendResponse(
            ResourceUser::make($user->fresh()),
            'Avatar removed successfully'
        );
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request): JsonResponse
    {
        // حذف كل الـ Tokens
        $request->user()->tokens()->delete();

        // حذف Cookie
        $cookie = Cookie::forget('auth_token');

        return $this->sendResponse([], 'Logged out from all devices')->withCookie($cookie);
    }

    /**
     * Handle avatar file upload
     */
    private function handleAvatarUpload(User $user, $avatarFile, array &$validated): void
    {
        // Delete old avatar if exists
        if ($user->avatar_public_id) {
            $this->cloudinaryService->deleteFile($user->avatar_public_id);
        }

        // Upload new avatar
        $result = $this->cloudinaryService->uploadFile($avatarFile);

        if (empty($result['secure_url']) && empty($result['url'])) {
            throw new \Exception('Failed to upload avatar to Cloudinary');
        }

        $validated['avatar'] = $result['secure_url'] ?? $result['url'];
        $validated['avatar_public_id'] = $result['public_id'] ?? null;
    }
}