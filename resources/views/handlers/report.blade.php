@extends('layout.dashboard')

@section('title','Business Report & Queue Status')

@section('content')
    <!-- Header with Navigation -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Business Analytics</h2>
                <p class="text-gray-600 text-sm mt-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Real-time business metrics and order queue monitoring
                </p>
            </div>
        </div>
    </div>

    <!-- Orders Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Total Orders -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Orders</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($totalOrders) }}</h3>
                    <p class="text-xs text-blue-100">All time orders</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg" style="width: 100%"></div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-yellow-100 text-sm font-medium mb-1">Pending Orders</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($pendingOrders) }}</h3>
                    <p class="text-xs text-yellow-100">
                        {{ $totalOrders > 0 ? number_format(($pendingOrders / $totalOrders) * 100, 1) : 0 }}% of total
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg animate-pulse" 
                     style="width: {{ $totalOrders > 0 ? ($pendingOrders / $totalOrders) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-green-100 text-sm font-medium mb-1">Completed Orders</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($completedOrders) }}</h3>
                    <p class="text-xs text-green-100">
                        {{ $totalOrders > 0 ? number_format(($completedOrders / $totalOrders) * 100, 1) : 0 }}% success rate
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg" 
                     style="width: {{ $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Cancelled Orders -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-red-100 text-sm font-medium mb-1">Cancelled Orders</p>
                    <h3 class="text-4xl font-bold mb-2">{{ number_format($cancelledOrders) }}</h3>
                    <p class="text-xs text-red-100">
                        {{ $totalOrders > 0 ? number_format(($cancelledOrders / $totalOrders) * 100, 1) : 0 }}% cancelled
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div class="bg-white h-2 rounded-full shadow-lg" 
                     style="width: {{ $totalOrders > 0 ? ($cancelledOrders / $totalOrders) * 100 : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                <h3 class="text-sm font-semibold text-purple-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                    Total Revenue
                </h3>
            </div>
            <div class="p-6">
                <div class="text-4xl font-bold text-gray-900 mb-2">
                    ${{ number_format($totalRevenue, 2) }}
                </div>
                <p class="text-sm text-gray-500">All time earnings</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Revenue Growth</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                            Trending
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month Revenue -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-100 px-6 py-4 border-b border-green-200">
                <h3 class="text-sm font-semibold text-green-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    This Month
                </h3>
            </div>
            <div class="p-6">
                <div class="text-4xl font-bold text-green-600 mb-2">
                    ${{ number_format($revenueThisMonth, 2) }}
                </div>
                <p class="text-sm text-gray-500">Current month revenue</p>
                @if($revenueLastMonth > 0)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold 
                                {{ (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    @if((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 >= 0)
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    @else
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    @endif
                                </svg>
                                {{ number_format(abs((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100), 1) }}%
                            </span>
                            <span class="text-xs text-gray-500">vs last month</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Last Month Revenue -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Last Month
                </h3>
            </div>
            <div class="p-6">
                <div class="text-4xl font-bold text-gray-700 mb-2">
                    ${{ number_format($revenueLastMonth, 2) }}
                </div>
                <p class="text-sm text-gray-500">Previous month revenue</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Comparison Base</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                            Reference
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Status -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Queue Status
                    </h3>
                    <p class="text-sm text-gray-500">Task processing overview</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 animate-pulse"></span>
                    Live
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 p-6">
            <!-- Pending Tasks -->
            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-200 rounded-full mb-3">
                    <svg class="w-8 h-8 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-blue-700 mb-1">{{ $queueStats['pending_tasks'] }}</div>
                <div class="text-sm font-semibold text-blue-900">Pending</div>
            </div>

            <!-- Processing Tasks -->
            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-200 rounded-full mb-3">
                    <svg class="w-8 h-8 text-purple-700 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-purple-700 mb-1">{{ $queueStats['processing_tasks'] }}</div>
                <div class="text-sm font-semibold text-purple-900">Processing</div>
            </div>

            <!-- Completed Tasks -->
            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-200 rounded-full mb-3">
                    <svg class="w-8 h-8 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-green-700 mb-1">{{ $queueStats['completed_tasks'] }}</div>
                <div class="text-sm font-semibold text-green-900">Completed</div>
            </div>

            <!-- Failed Tasks -->
            <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl border-2 border-red-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-200 rounded-full mb-3">
                    <svg class="w-8 h-8 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-red-700 mb-1">{{ $queueStats['failed_tasks'] }}</div>
                <div class="text-sm font-semibold text-red-900">Failed</div>
            </div>
        </div>
    </div>

    <!-- Inventory & Users Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Inventory Status -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-5 border-b border-orange-200">
                <h3 class="text-lg font-bold text-orange-900 mb-1 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Inventory Status
                </h3>
                <p class="text-sm text-orange-700">Product stock overview</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-200 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-700">Total Products</span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{ number_format($totalProducts) }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-200 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-700">Low Stock (≤ 5)</span>
                    </div>
                    <span class="text-3xl font-bold text-yellow-600">{{ number_format($lowStockProducts) }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-xl border border-red-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-200 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-700">Out of Stock</span>
                    </div>
                    <span class="text-3xl font-bold text-red-600">{{ number_format($outOfStockProducts) }}</span>
                </div>
            </div>
        </div>

        <!-- User Activity -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-5 border-b border-indigo-200">
                <h3 class="text-lg font-bold text-indigo-900 mb-1 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    User Activity
                </h3>
                <p class="text-sm text-indigo-700">User engagement metrics</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-200 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-700">Total Users</span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{ number_format($totalUsers) }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-200 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-700">Active (30 days)</span>
                    </div>
                    <span class="text-3xl font-bold text-green-600">{{ number_format($activeUsersThisMonth) }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-200 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-purple-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-700">Engagement Rate</span>
                    </div>
                    <span class="text-3xl font-bold text-purple-600">
                        {{ $totalUsers > 0 ? number_format(($activeUsersThisMonth / $totalUsers) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Trend Chart -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        Order Trend
                    </h3>
                    <p class="text-sm text-gray-500">Last 30 days performance</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    30 Days
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orderTrend as $trend)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ $trend->date }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                                        <span class="text-sm font-bold text-blue-700">{{ $trend->count }}</span>
                                    </span>
                                    <span class="text-xs text-gray-500">orders</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-bold text-green-600">${{ number_format($trend->revenue, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[100px]">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" 
                                             style="width: {{ $orderTrend->max('revenue') > 0 ? min(100, ($trend->revenue / $orderTrend->max('revenue')) * 100) : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600">
                                        {{ $orderTrend->max('revenue') > 0 ? number_format(($trend->revenue / $orderTrend->max('revenue')) * 100, 0) : 0 }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium">No trend data available</p>
                                    <p class="text-gray-400 text-sm mt-1">Data will appear once orders are placed</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection