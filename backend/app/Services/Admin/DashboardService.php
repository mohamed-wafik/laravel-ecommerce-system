<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboard(int $days = 30): array
    {
        $cacheKey = "admin_dashboard_{$days}";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(2),
            function () use ($days) {
                return [
                    'summary' => $this->getSummary($days),
                    'sales' => $this->getSalesByDay($days),
                    'orders_by_status' => $this->getOrdersByStatus($days),
                    'top_products' => $this->getTopProducts($days),
                    'sales_by_category' => $this->getSalesByCategory($days),
                    'customers' => $this->getCustomerAnalytics($days),
                    'inventory' => $this->getInventoryAnalytics(),
                ];
            }
        );
    }

    private function getSummary(int $days): array
    {
        $startDate = now()->subDays($days);

        $stats = Order::query()
            ->whereIn('status', [
                'paid',
                'shipped',
                'delivered',
            ])
            ->where('created_at', '>=', $startDate)
            ->selectRaw('
                COALESCE(SUM(total), 0) as revenue,
                COUNT(*) as orders
            ')
            ->first();

        $productsSold = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', [
                'paid',
                'shipped',
                'delivered',
            ])
            ->where('orders.created_at', '>=', $startDate)
            ->sum('order_items.quantity');

        $customers = User::query()
            ->where('role', 'customer')
            ->where('created_at', '>=', $startDate)
            ->count();

        $revenue = (float) $stats->revenue;
        $orders = (int) $stats->orders;

        return [
            'revenue' => $revenue,
            'orders' => $orders,
            'products_sold' => (int) $productsSold,
            'customers' => $customers,
            'average_order_value' => $orders > 0
                ? round($revenue / $orders, 2)
                : 0,
        ];
    }

    private function getSalesByDay(int $days): array
    {
        return Order::query()
            ->whereIn('status', [
                'paid',
                'shipped',
                'delivered',
            ])
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('
                DATE(created_at) as date,
                SUM(total) as revenue,
                COUNT(*) as orders
            ')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => [
                'date' => $row->date,
                'revenue' => (float) $row->revenue,
                'orders' => (int) $row->orders,
            ])
            ->toArray();
    }

    private function getOrdersByStatus(int $days): array
    {
        return Order::query()
            ->where('created_at', '>=', now()->subDays($days))
            ->select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'status' => $row->status,
                'count' => (int) $row->count,
            ])
            ->toArray();
    }

    private function getTopProducts(int $days): array
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('orders.status', [
                'paid',
                'shipped',
                'delivered',
            ])
            ->where('orders.created_at', '>=', now()->subDays($days))
            ->select(
                'products.id',
                'products.name'
            )
            ->selectRaw('SUM(order_items.quantity) as sold')
            ->selectRaw('SUM(order_items.quantity * order_items.price_snapshot) as revenue')
            ->groupBy(
                'products.id',
                'products.name'
            )
            ->orderByDesc('sold')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'sold' => (int) $row->sold,
                'revenue' => (float) $row->revenue,
            ])
            ->toArray();
    }

    private function getSalesByCategory(int $days): array
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->whereIn('orders.status', [
                'paid',
                'shipped',
                'delivered',
            ])
            ->where('orders.created_at', '>=', now()->subDays($days))
            ->select(
                'categories.id',
                'categories.name'
            )
            ->selectRaw('
                SUM(order_items.quantity * order_items.price_snapshot) as revenue
            ')
            ->groupBy(
                'categories.id',
                'categories.name'
            )
            ->orderByDesc('revenue')
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'revenue' => (float) $row->revenue,
            ])
            ->toArray();
    }

    private function getCustomerAnalytics(int $days): array
    {
        $startDate = now()->subDays($days);

        $newCustomers = User::query()
            ->where('role', 'customer')
            ->where('created_at', '>=', $startDate)
            ->count();

        $returningCustomers = DB::table('orders')
            ->whereIn('status', [
                'paid',
                'shipped',
                'delivered',
            ])
            ->where('created_at', '>=', $startDate)
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        return [
            'new' => $newCustomers,
            'returning' => $returningCustomers,
        ];
    }

    private function getInventoryAnalytics(): array
    {
        $lowStockThreshold = 10;

        return [
            'total' => Product::query()->count(),

            'active' => Product::query()
                ->where('is_active', true)
                ->count(),

            'low_stock' => Product::query()
                ->where('is_active', true)
                ->whereBetween('stock_quantity', [1, $lowStockThreshold])
                ->count(),

            'out_of_stock' => Product::query()
                ->where('is_active', true)
                ->where('stock_quantity', 0)
                ->count(),
        ];
    }
}