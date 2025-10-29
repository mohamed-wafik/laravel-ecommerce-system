<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $countUsers = User::count();
        $countProducts = Product::count();
        $countCategories = Category::count();
        $countOrders = Order::count();

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->take(10)
            ->get(['id', 'title', 'stock']);

        $recentOrders = Order::where('order_date', '>=', now()->subDays(7))
            ->orderBy('order_date', 'desc')
            ->take(10)
            ->get(['id', 'user_id', 'total_amount', 'status', 'order_date']);

        $usersWeekEnd = User::where('created_at', '>=', now()->subWeek())->count();

        $topProducts = Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get(['id', 'title', 'price']);

        $totalSalesYear = Order::select(DB::raw('SUM(total_amount) as total_sales'))
            ->where('order_date', '>=', now()->subYear())
            ->first();

        $statusOfOrders = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $salesLast7Days = Order::select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->where('order_date', '>=', now()->subDays(7))
            ->where('status', 'completed')
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date', 'asc')
            ->get();

        $topCountrySales = Order::select('country', DB::raw('COUNT(*) as total_sales'))
            ->groupBy('country')
            ->orderByDesc('total_sales')
            ->first();

        $salesLast30Days = Order::select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->where('order_date', '>=', now()->subDays(30))
            ->where('status', 'completed')
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'countUsers',
            'countProducts',
            'countCategories',
            'countOrders',
            'lowStockProducts',
            'recentOrders',
            'usersWeekEnd',
            'topProducts',
            'totalSalesYear',
            'statusOfOrders',
            'salesLast7Days',
            'topCountrySales',
            'salesLast30Days'
        ));
    }
}
