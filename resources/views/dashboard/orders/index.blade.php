@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section('content')
@php
    // ====== Static Fake Data for Testing ======
    $totalOrders = 8;
    $pendingOrders = 2;
    $completedOrders = 5;
    $totalRevenue = 1240.75;

    $orders = collect([
        (object)[
            'id' => 1001,
            'order_number' => 'ORD-2025-001',
            'user' => (object)[
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'avatar' => 'https://i.pravatar.cc/100?u=john'
            ],
            'user_id' => 1,
            'created_at' => now()->subDays(1),
            'items_count' => 3,
            'total_amount' => 125.50,
            'status' => 'pending'
        ],
        (object)[
            'id' => 1002,
            'order_number' => 'ORD-2025-002',
            'user' => (object)[
                'name' => 'Emily Carter',
                'email' => 'emily@example.com',
                'avatar' => 'https://i.pravatar.cc/100?u=emily'
            ],
            'user_id' => 2,
            'created_at' => now()->subDays(2),
            'items_count' => 5,
            'total_amount' => 349.99,
            'status' => 'completed'
        ],
        (object)[
            'id' => 1003,
            'order_number' => 'ORD-2025-003',
            'user' => (object)[
                'name' => 'Michael Smith',
                'email' => 'michael@example.com',
                'avatar' => 'https://i.pravatar.cc/100?u=mike'
            ],
            'user_id' => 3,
            'created_at' => now()->subDays(3),
            'items_count' => 2,
            'total_amount' => 89.99,
            'status' => 'processing'
        ],
        (object)[
            'id' => 1004,
            'order_number' => 'ORD-2025-004',
            'user' => (object)[
                'name' => 'Sophia Turner',
                'email' => 'sophia@example.com',
                'avatar' => 'https://i.pravatar.cc/100?u=sophia'
            ],
            'user_id' => 4,
            'created_at' => now()->subDays(5),
            'items_count' => 4,
            'total_amount' => 230.25,
            'status' => 'completed'
        ],
        (object)[
            'id' => 1005,
            'order_number' => 'ORD-2025-005',
            'user' => (object)[
                'name' => 'Oliver Johnson',
                'email' => 'oliver@example.com',
                'avatar' => 'https://i.pravatar.cc/100?u=oliver'
            ],
            'user_id' => 5,
            'created_at' => now()->subDays(7),
            'items_count' => 1,
            'total_amount' => 55.99,
            'status' => 'cancelled'
        ],
    ]);
@endphp

    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
                <p class="text-gray-600 mt-1">Manage customer orders and track order status</p>
            </div>

            <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-file-export"></i> Export
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $totalOrders }}</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl">
                    <i class="fa-solid fa-shopping-cart text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $pendingOrders }}</h3>
                </div>
                <div class="p-3 bg-yellow-100 rounded-xl">
                    <i class="fa-solid fa-clock text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $completedOrders }}</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-xl">
                    <i class="fa-solid fa-check-circle text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="p-3 bg-purple-100 rounded-xl">
                    <i class="fa-solid fa-dollar-sign text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead>
                    <tr class="bg-gradient-to-r from-primary-600 to-primary-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">ORDER</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">CUSTOMER</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">DATE</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">ITEMS</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">AMOUNT</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">STATUS</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-receipt text-lg text-gray-400"></i>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->order_number }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full" src="{{ $order->user->avatar }}" alt="{{ $order->user->name }}">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order->created_at->format('M j, Y') }}
                                <div class="text-gray-500 text-xs">{{ $order->created_at->format('g:i A') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $order->items_count }} items
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                                        'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    <i class="fa-solid fa-circle mr-1 text-xs"></i> {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="View">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                    <button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg" title="Edit">
                                        <i class="fa-solid fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="printOrder({{ $order->id }})" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg" title="Print">
                                        <i class="fa-solid fa-print text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-6 py-4 text-right text-sm font-semibold text-gray-900 border-t border-gray-200">
            Total Orders: {{ count($orders) }}
        </div>
    </div>
@endsection

@push('script')
<script>
    function printOrder(orderId) {

    }
</script>
@endpush