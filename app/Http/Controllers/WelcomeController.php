<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route("login");
        }
        if (Auth::check() && Auth::user()->role === "admin") {
            return redirect()->route('dashboard.index');
        }

        if (Auth::check()) {
            $recentOrders = Order::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $recommendedProducts = Product::with('category')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            return view('welcome-user', compact('recentOrders', 'recommendedProducts'));
        }
    }
}