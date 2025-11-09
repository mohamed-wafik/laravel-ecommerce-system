<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // If user is logged in and is admin, redirect to dashboard
        if (Auth::check() && Auth::user()->role === "admin") {
            return redirect()->route('dashboard.index');
        }

        // If user is logged in but not admin, show user welcome page with data
        if (Auth::check()) {
            // Get user's recent orders
            $recentOrders = Order::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Get recommended products (for now, just get recent products)
            $recommendedProducts = Product::with('category')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            return view('welcome-user', compact('recentOrders', 'recommendedProducts'));
        }

        // If no user is logged in, show guest welcome page with featured products
        $featuredProducts = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('welcome', compact('featuredProducts'));
    }
}