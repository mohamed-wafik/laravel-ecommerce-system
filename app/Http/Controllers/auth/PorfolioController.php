<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PorfolioController extends Controller
{
    private CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }   

    public function index() {
        return view("dashboard.settings.index");
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with("error", "User not found!");
        }

        if (Auth::id() !== +$id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            "name" => "nullable|string|min:2|max:50",
            "email" => "nullable|email|unique:users,email," . $user->id,
            "image" => "nullable|image|mimes:jpg,jpeg,png,gif|max:2048",
        ]);

        if ($request->hasFile('image')) {
            
            if ($user->avatar_public_id) {
                $check = $this->cloudinaryService->deleteFile($user->avatar_public_id);
                if (!$check) {
                    return redirect()->back()
                        ->with('error', 'Failed to delete existing avatar from Cloudinary!');
                }
            }

            $result = $this->cloudinaryService->uploadFile($request->file('image'));


            $validated['avatar'] = $result['secure_url'] ?? ($result['url'] ?? null);
            $validated['avatar_public_id'] = $result['public_id'] ?? null;
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with("error", "User not found!");
        }

        if (Auth::id() !== +$id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            "currentPassword" => "required|string|min:6|max:50",
            "password" => "required|string|min:6|max:50|confirmed",
        ]);

        if (!Hash::check($validated["currentPassword"], $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect!');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function removeAvatar($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== +$id) {
             return response()->json([
                "message" => "Unauthorized action.",
                "success" => false
            ], 403);
        }

        if ($user->avatar_public_id) {
            $result =$this->cloudinaryService->deleteFile($user->avatar_public_id);
            if (!$result) {
                return response()->json([
                    "message" => "Failed to delete avatar from Cloudinary!",
                    "success" => false
                ], 500);
            }
        }

        $user->update(['avatar' => null]);

        return response()->json([
            "message" => "Avatar removed successfully!",
            "success" => true
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== +$id && Auth::user()->role === "admin") {
            abort(403, 'Unauthorized action.');
        }

        if ($user->avatar_public_id) {
            $result = $this->cloudinaryService->deleteFile($user->avatar_public_id);

            if (!$result) {
                return redirect()->back()
                    ->with('error', 'Failed to delete avatar from Cloudinary!');
            }
        }

        $user->delete();

        if (Auth::id() === $id) {
            Auth::logout();
            return redirect()->route('/')->with('success', 'Your account has been deleted successfully.');
        }

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}