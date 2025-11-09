<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }
    public function showRegisterForm() {
       return view('auth.register');
    }
    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required|min:6",
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')
                ->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|min:3|max:50",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6|max:50", 
        ]);

        $user = User::create($validated);

        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}