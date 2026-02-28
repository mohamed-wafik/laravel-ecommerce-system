<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HandlerController extends Controller
{
    public function index()
    {
        $path = app_path('Handlers');
        $files = [];
        foreach (glob($path . '/*.php') as $file) {
            $files[] = [
                'name' => basename($file),
                'path' => $file,
                'modified' => date('Y-m-d H:i:s', filemtime($file)),
                'size' => filesize($file),
            ];
        }

        // Apply search filter
        $search = request('search', '');
        if ($search) {
            $files = array_filter($files, function($file) use ($search) {
                return stripos($file['name'], $search) !== false;
            });
        }

        // Sort by name
        usort($files, fn($a, $b) => strcmp($a['name'], $b['name']));

        return view('handlers.index', compact('files'));
    }

    public function report()
    {
        // Business app metrics and queue status
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $completedOrders = Order::where('order_status', 'completed')->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();

        $totalRevenue = Order::sum('total');
        $revenueThisMonth = Order::where('created_at', '>=', now()->startOfMonth())->sum('total');
        $revenueLastMonth = Order::where('created_at', '<', now()->startOfMonth())->where('created_at', '>=', now()->subMonth()->startOfMonth())->sum('total');

        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();

        $totalUsers = User::count();
        $activeUsersThisMonth = User::where('updated_at', '>=', now()->subDays(30))->count();

        $orderTrend = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $queueStats = [
            'pending_tasks' => $pendingOrders,
            'processing_tasks' => Order::where('order_status', 'processing')->count(),
            'completed_tasks' => $completedOrders,
            'failed_tasks' => Order::where('order_status', 'failed')->count(),
        ];

        return view('handlers.report', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue',
            'revenueThisMonth',
            'revenueLastMonth',
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalUsers',
            'activeUsersThisMonth',
            'orderTrend',
            'queueStats'
        ));
    }
}