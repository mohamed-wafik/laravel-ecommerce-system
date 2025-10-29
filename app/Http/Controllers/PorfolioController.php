<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PorfolioController extends Controller
{
   public function index() {
        return view("dashboard.settings.index");
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with("error", "User not found!");
        }

        // if (Auth::id() !== $id) {
        //     abort(403, 'Unauthorized action.');
        // }

        $validated = $request->validate([
            "name" => "nullable|string|min:2|max:50",
            "email" => "nullable|email|unique:users,email," . $user->id,
            "image" => "nullable|image|mimes:jpg,jpeg,png,gif|max:2048",
        ]);

        // Handle avatar upload
        if ($request->hasFile('image')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('image')->store('images', 'public');
            $validated['avatar'] = $path;
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

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
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

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        if (Auth::id() === $id) {
            Auth::logout();
            return redirect()->route('/')->with('success', 'Your account has been deleted successfully.');
        }

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
