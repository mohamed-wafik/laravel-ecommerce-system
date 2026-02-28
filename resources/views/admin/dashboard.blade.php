@extends('layout.dashboard')

@section("content")
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Users</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($countUsers ?? 0) }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm">
                            @if(($userPercentChange ?? 0) >= 0)
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1 transform rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ number_format(abs($userPercentChange ?? 0), 1) }}%
                        </span>
                        <span class="text-xs text-blue-100">vs last week</span>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg transition-all duration-500" style="width: {{ min(100, abs($userPercentChange ?? 0)) }}%"></div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-emerald-100 text-sm font-medium mb-1">Total Products</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($countProducts ?? 0) }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm">
                            @if(($productPercentChange ?? 0) >= 0)
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1 transform rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ number_format(abs($productPercentChange ?? 0), 1) }}%
                        </span>
                        <span class="text-xs text-emerald-100">vs last week</span>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg transition-all duration-500" style="width: {{ min(100, abs($productPercentChange ?? 0)) }}%"></div>
            </div>
        </div>

        <!-- Total Categories Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-purple-100 text-sm font-medium mb-1">Total Categories</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($countCategories ?? 0) }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm">
                            @if(($categoryPercentChange ?? 0) >= 0)
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1 transform rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ number_format(abs($categoryPercentChange ?? 0), 1) }}%
                        </span>
                        <span class="text-xs text-purple-100">vs last week</span>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg transition-all duration-500" style="width: {{ min(100, abs($categoryPercentChange ?? 0)) }}%"></div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-amber-100 text-sm font-medium mb-1">Total Orders</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($countOrders ?? 0) }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm">
                            @if(($orderPercentChange ?? 0) >= 0)
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1 transform rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ number_format(abs($orderPercentChange ?? 0), 1) }}%
                        </span>
                        <span class="text-xs text-amber-100">vs last week</span>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg transition-all duration-500" style="width: {{ min(100, abs($orderPercentChange ?? 0)) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Sales Overview</h3>
                        <p class="text-sm text-gray-500">Track your sales performance</p>
                    </div>
                    <div class="inline-flex items-center bg-gray-100 rounded-lg p-1">
                        <button id="btn-salesChart7Days"
                            class="px-4 py-2 text-sm font-semibold rounded-md transition-all duration-200 bg-white text-blue-600 shadow-sm">
                            7 Days
                        </button>
                        <button id="btn-salesChart30Days"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 rounded-md transition-all duration-200">
                            30 Days
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div id="salesChart7Days">
                    <canvas height="300"></canvas>
                </div>
                <div id="salesChart30Days" class="hidden">
                    <canvas height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Orders Status Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Orders Status</h3>
                <p class="text-sm text-gray-500">Distribution of order statuses</p>
            </div>
            <div class="p-6">
                @if(isset($statusOfOrders) && $statusOfOrders->isNotEmpty())
                    <canvas id="ordersStatusChart" height="300"></canvas>
                @else
                    <div class="flex items-center justify-center h-[300px] text-gray-400">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="font-medium">No order data available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Recent Orders</h3>
                        <p class="text-sm text-gray-500">Latest customer orders</p>
                    </div>
                    <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        View All →
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders ?? [] as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">#{{ $order['id'] ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-xs mr-3">
                                            U
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">User {{ $order['user_id'] ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">${{ number_format($order['total_amount'] ?? 0, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = $order['status'] ?? 'unknown';
                                        $statusConfig = [
                                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dot' => 'bg-yellow-400', 'label' => 'Pending'],
                                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-400', 'label' => 'Completed'],
                                            'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-400', 'label' => 'Processing', 'pulse' => true],
                                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dot' => 'bg-red-400', 'label' => 'Cancelled'],
                                        ];
                                        $config = $statusConfig[$status] ?? $statusConfig['cancelled'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                        <span class="w-1.5 h-1.5 {{ $config['dot'] }} rounded-full mr-2 {{ isset($config['pulse']) ? 'animate-pulse' : '' }}"></span>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="font-medium">No recent orders</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Low Stock Alert</h3>
                        <p class="text-sm text-gray-500">Products need restocking</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ count($lowStockProducts ?? []) }} Items
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($lowStockProducts ?? [] as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">{{ $product->title ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="text-sm font-bold text-gray-900 mr-2">{{ $product->stock ?? 0 }}</span>
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[60px]">
                                            <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full" 
                                                 style="width: {{ min(100, (($product->stock ?? 0) / 10) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(($product->stock ?? 0) <= 2)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-2 animate-pulse"></span>
                                            Critical
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                            <span class="w-1.5 h-1.5 bg-orange-400 rounded-full mr-2"></span>
                                            Low Stock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <p class="font-medium">All products well stocked!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bottom Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Top Country -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Top Sales Country</h3>
                <p class="text-sm text-gray-500">Best performing region</p>
            </div>
            <div class="p-6">
                @if(isset($topCitySales) && $topCitySales)
                    <div class="mb-4">
                        <iframe 
                            class="w-full h-48 rounded-xl border-2 border-gray-200 shadow-sm"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q={{ urlencode($topCitySales->city ?? 'World') }}&output=embed">
                        </iframe>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-900 mb-1">{{ $topCitySales->city ?? 'N/A' }}</p>
                                <p class="text-2xl font-bold text-blue-700">{{ $topCitySales->total_sales ?? 0 }}</p>
                                <p class="text-xs text-blue-600 mt-1">Total Orders</p>
                            </div>
                            <div class="bg-blue-200 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center h-64 text-gray-400">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-medium">No location data available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- New Users -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">New Users</h3>
                <p class="text-sm text-gray-500">This week's growth</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            {{ $usersWeekEnd ?? 0 }}
                        </span>
                        <p class="text-sm text-gray-500 mt-1">New registrations</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-100 to-emerald-100 p-4 rounded-xl">
                        <span class="text-2xl font-bold text-green-700">
                            @if(($countUsers ?? 1) > 0)
                                +{{ number_format((($usersWeekEnd ?? 0) / ($countUsers ?? 1)) * 100, 0) }}%
                            @else
                                +0%
                            @endif
                        </span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Progress</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format((($usersWeekEnd ?? 0) / max(1, $countUsers ?? 1)) * 100, 1) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full shadow-lg transition-all duration-500"
                             style="width: {{ min(100, (($usersWeekEnd ?? 0) / max(1, $countUsers ?? 1)) * 100) }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">
                        <span class="font-semibold text-gray-700">{{ $usersWeekEnd ?? 0 }}</span> out of 
                        <span class="font-semibold text-gray-700">{{ $countUsers ?? 0 }}</span> total users
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Quick Actions</h3>
                <p class="text-sm text-gray-500">Common tasks</p>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('products.create') }}" class="group w-full flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-200 border-2 border-transparent hover:border-blue-300">
                    <div class="flex items-center">
                        <div class="bg-blue-200 p-2 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="ml-3 font-semibold text-gray-900">Add New Product</span>
                    </div>
                    <svg class="w-5 h-5 text-blue-600 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
                @if(Route::has('handlers.report'))
                    <a href="{{ route('handlers.report') }}" class="group w-full flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-100 hover:from-green-100 hover:to-emerald-200 rounded-xl transition-all duration-200 border-2 border-transparent hover:border-green-300">
                        <div class="flex items-center">
                            <div class="bg-green-200 p-2 rounded-lg group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <span class="ml-3 font-semibold text-gray-900">View Reports</span>
                        </div>
                        <svg class="w-5 h-5 text-green-600 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endif
                @if(Route::has('dashboard.clear-cache'))
                    <button onclick="clearDashboardCache()" class="group w-full flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-200 border-2 border-transparent hover:border-purple-300">
                        <div class="flex items-center">
                            <div class="bg-purple-200 p-2 rounded-lg group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-purple-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="ml-3 font-semibold text-gray-900">Refresh Data</span>
                        </div>
                        <svg class="w-5 h-5 text-purple-600 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Products Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Top 5 Products</h3>
                    <p class="text-sm text-gray-500">Best selling products</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Top Performers
                    </span>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topProducts ?? [] as $index => $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($index == 0)
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            1
                                        </div>
                                    @elseif($index == 1)
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            2
                                        </div>
                                    @elseif($index == 2)
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            3
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-700 font-bold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold mr-3">
                                        {{ substr($product->title ?? 'P', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $product->title ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $product->id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">${{ number_format($product->price ?? 0, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="text-sm font-bold text-gray-900 mr-2">{{ $product->orders_count ?? 0 }}</span>
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                    </svg>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $maxOrders = isset($topProducts) && $topProducts->isNotEmpty() ? $topProducts->max('orders_count') : 1;
                                    $percentage = $maxOrders > 0 ? (($product->orders_count ?? 0) / $maxOrders) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[100px]">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" 
                                             style="width: {{ min(100, $percentage) }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600">
                                        {{ number_format($percentage, 0) }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium">No products found</p>
                                    <p class="text-gray-400 text-sm mt-1">Add products to see them here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart (7 Days)
    const sales7DaysCtx = document.querySelector('#salesChart7Days canvas')?.getContext('2d');
    if (sales7DaysCtx) {
        const sales7DaysChart = new Chart(sales7DaysCtx, {
            type: 'line',
            data: {
                labels: @json(($salesLast7Days ?? collect())->pluck('date')),
                datasets: [{
                    label: 'Sales',
                    data: @json(($salesLast7Days ?? collect())->pluck('total_sales')),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: { font: { size: 12 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 12 } }
                    }
                }
            }
        });
    }

    // Sales Chart (30 Days)
    const sales30DaysCtx = document.querySelector('#salesChart30Days canvas')?.getContext('2d');
    if (sales30DaysCtx) {
        const sales30DaysChart = new Chart(sales30DaysCtx, {
            type: 'line',
            data: {
                labels: @json(($salesLast30Days ?? collect())->pluck('date')),
                datasets: [{
                    label: 'Sales (30 Days)',
                    data: @json(($salesLast30Days ?? collect())->pluck('total_sales')),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: { font: { size: 12 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 12 } }
                    }
                }
            }
        });
    }

    // Toggle Button Handler
    const btn7 = document.getElementById('btn-salesChart7Days');
    const btn30 = document.getElementById('btn-salesChart30Days');
    const chart7 = document.getElementById('salesChart7Days');
    const chart30 = document.getElementById('salesChart30Days');

    if (btn7 && btn30 && chart7 && chart30) {
        btn7.addEventListener('click', () => {
            chart7.classList.remove('hidden');
            chart30.classList.add('hidden');
            btn7.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            btn7.classList.remove('text-gray-600');
            btn30.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
            btn30.classList.add('text-gray-600');
        });

        btn30.addEventListener('click', () => {
            chart30.classList.remove('hidden');
            chart7.classList.add('hidden');
            btn30.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            btn30.classList.remove('text-gray-600');
            btn7.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
            btn7.classList.add('text-gray-600');
        });
    }

    // Orders Status Chart
    const ordersStatusCtx = document.getElementById('ordersStatusChart')?.getContext('2d');
    @if(isset($statusOfOrders) && $statusOfOrders->isNotEmpty())
        if (ordersStatusCtx) {
            new Chart(ordersStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($statusOfOrders->pluck('status')),
                    datasets: [{
                        data: @json($statusOfOrders->pluck('total')),
                        backgroundColor: [
                            '#10b981', // green
                            '#3b82f6', // blue
                            '#f59e0b', // amber
                            '#ef4444'  // red
                        ],
                        borderWidth: 4,
                        borderColor: '#fff',
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: { size: 13, weight: '600' },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 }
                        }
                    }
                }
            });
        }
    @endif

    // Clear Dashboard Cache Function
    function clearDashboardCache() {
        if (confirm('Are you sure you want to refresh the dashboard data?')) {
        }
    }
</script>
@endpush