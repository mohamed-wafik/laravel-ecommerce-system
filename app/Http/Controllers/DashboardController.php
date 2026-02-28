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
        // Basic Counts
        $countUsers = User::count();
        $countProducts = Product::count();
        $countCategories = Category::count();
        $countOrders = Order::count();
        
        // Percent Changes
        $userPercentChange = $countUsers > 0 
            ? (User::where('created_at', '>=', now()->subWeek())->count() / $countUsers) * 100 
            : 0;
            
        $productPercentChange = $countProducts > 0 
            ? (Product::where('created_at', '>=', now()->subWeek())->count() / $countProducts) * 100 
            : 0;
            
        $orderPercentChange = $countOrders > 0 
            ? (Order::where('created_at', '>=', now()->subWeek())->count() / $countOrders) * 100 
            : 0;
            
        $categoryPercentChange = $countCategories > 0 
            ? (Category::where('created_at', '>=', now()->subWeek())->count() / $countCategories) * 100 
            : 0;

        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->take(10)
            ->get();

        // Recent Orders
        $recentOrders = Order::with('items.product')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Users this week
        $usersWeekEnd = User::where('created_at', '>=', now()->subWeek())->count();

        // ✅ FIXED: Top Products - استخدام subquery بدلاً من groupBy
        $topProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->select(
                'products.id',
                'products.title',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.total) as revenue'),
                DB::raw('COUNT(orders.id) as orders_count')
            )
            ->groupBy('products.id', 'products.title', 'products.price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ✅ Alternative: استخدام WITH subquery (أكثر كفاءة)
        // $topProducts = Product::select('products.*')
        //     ->selectSub(function ($query) {
        //         $query->from('order_items')
        //             ->join('orders', 'order_items.order_id', '=', 'orders.id')
        //             ->whereColumn('order_items.product_id', 'products.id')
        //             ->where('orders.payment_status', 'paid')
        //             ->selectRaw('COALESCE(SUM(order_items.quantity), 0)');
        //     }, 'total_sold')
        //     ->selectSub(function ($query) {
        //         $query->from('order_items')
        //             ->join('orders', 'order_items.order_id', '=', 'orders.id')
        //             ->whereColumn('order_items.product_id', 'products.id')
        //             ->where('orders.payment_status', 'paid')
        //             ->selectRaw('COALESCE(SUM(order_items.total), 0)');
        //     }, 'revenue')
        //     ->orderByDesc('total_sold')
        //     ->limit(5)
        //     ->get();

        // Total Sales this Year
        $totalSalesYear = Order::where('created_at', '>=', now()->subYear())
            ->where('payment_status', 'paid')
            ->sum('total') ?? 0;

        // Order Status Distribution
        $statusOfOrders = Order::select('order_status as status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->get();

        // Sales Last 7 Days
        $salesLast7Days = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->where('payment_status', 'paid')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // Top City Sales
        $topCitySales = Order::select('city', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total) as total_sales'))
            ->where('payment_status', 'paid')
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderByDesc('total_sales')
            ->first();

        // Sales Last 30 Days
        $salesLast30Days = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->where('payment_status', 'paid')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        // Payment Method Stats
        $paymentMethodStats = Order::select('payment_method', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_method')
            ->get();

        // Revenue Summary
        $revenueSummary = [
            'today' => Order::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('total') ?? 0,
            'week' => Order::where('created_at', '>=', now()->subWeek())
                ->where('payment_status', 'paid')
                ->sum('total') ?? 0,
            'month' => Order::where('created_at', '>=', now()->subMonth())
                ->where('payment_status', 'paid')
                ->sum('total') ?? 0,
            'year' => $totalSalesYear
        ];

        // Average Order Value
        $averageOrderValue = Order::where('payment_status', 'paid')
            ->avg('total') ?? 0;

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
            'topCitySales',
            'salesLast30Days',
            'userPercentChange',
            'productPercentChange',
            'orderPercentChange',
            'categoryPercentChange',
            'paymentMethodStats',
            'revenueSummary',
            'averageOrderValue'
        ));
    }
}