@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
            <p class="text-gray-600 mt-1">View detailed information and orders for this user</p>
        </div>
        <a href="{{ route('dashboard.users') }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Users
        </a>
    </div>
</div>

<!-- USER CARD -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center flex-col md:flex-row gap-6">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            <img class="h-32 w-32 rounded-full border-4 border-gray-200 object-cover"
                 src="{{ $user->avatar ?? asset('storage/images/default_avatar.webp') }}"
                 alt="{{ $user->name }}"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random'" 
            >
        </div>

        <!-- Info -->
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ $user->name }}</h2>
            <p class="text-gray-600 mb-3">User ID: #{{ $user->id }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center gap-1.5">
                    <p class="text-gray-500">Email:</p>
                    <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <p class="text-gray-500">Phone:</p>
                    <p class="text-gray-900 font-medium">{{ $user->phone ?? 'N/A' }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <p class="text-gray-500">Role:</p>
                    @php
                        $roleColors = [
                            'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'user' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'moderator' => 'bg-green-100 text-green-800 border-green-200'
                        ];
                        $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $roleColor }}">
                        <i class="fa-solid fa-user-shield mr-1 text-xs"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <p class="text-gray-500">Joined:</p>
                    <p class="text-gray-900 font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- STATISTICS CARDS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium opacity-90">Total Orders</h3>
            <i class="fa-solid fa-shopping-cart text-2xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold">{{ $user->orders->count() }}</p>
    </div>

    <!-- Total Spent -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium opacity-90">Total Spent</h3>
            <i class="fa-solid fa-dollar-sign text-2xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold">
            ${{ number_format($user->orders->where('payment_status', 'paid')->sum('total'), 2) }}
        </p>
    </div>

    <!-- Pending Orders -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-sm p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium opacity-90">Pending</h3>
            <i class="fa-solid fa-clock text-2xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold">
            {{ $user->orders->where('order_status', 'pending')->count() }}
        </p>
    </div>

    <!-- Completed Orders -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium opacity-90">Delivered</h3>
            <i class="fa-solid fa-check-circle text-2xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold">
            {{ $user->orders->where('order_status', 'delivered')->count() }}
        </p>
    </div>
</div>

<!-- ORDERS TABLE -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Order History</h3>
        <span class="text-sm text-gray-500">{{ $user->orders->count() }} total orders</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px]">
            <thead>
                <tr class="bg-gradient-to-r from-primary-600 to-primary-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Order Number</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Order Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Payment Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($user->orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Order Number -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</span>
                                @if($order->payment_method === 'card')
                                    <i class="fa-solid fa-credit-card text-blue-500" title="Card Payment"></i>
                                @elseif($order->payment_method === 'cod')
                                    <i class="fa-solid fa-money-bill text-green-500" title="Cash on Delivery"></i>
                                @elseif($order->payment_method === 'wallet')
                                    <i class="fa-solid fa-wallet text-purple-500" title="Mobile Wallet"></i>
                                @endif
                            </div>
                        </td>

                        <!-- Customer Name -->
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $order->customer_email }}</p>
                            </div>
                        </td>

                        <!-- Order Status -->
                        <td class="px-6 py-4">
                            @php
                                $orderStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'delivered' => 'bg-green-100 text-green-800 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $orderStatusColor = $orderStatusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border {{ $orderStatusColor }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>

                        <!-- Payment Status -->
                        <td class="px-6 py-4">
                            @php
                                $paymentStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'paid' => 'bg-green-100 text-green-800 border-green-200',
                                    'failed' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $paymentStatusColor = $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border {{ $paymentStatusColor }}">
                                <i class="fa-solid fa-circle text-[6px] mr-1.5 self-center"></i>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>

                        <!-- Total Amount -->
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-bold text-gray-900">${{ number_format($order->total, 2) }}</p>
                                <p class="text-xs text-gray-500">
                                    Items: ${{ number_format($order->subtotal, 2) }}
                                </p>
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                                <p>{{ $order->created_at->format('M d, Y') }}</p>
                                <p class="text-xs">{{ $order->created_at->format('h:i A') }}</p>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors"
                                   title="View Details">
                                    <i class="fa-solid fa-eye mr-1"></i>
                                    View
                                </a>
                                
                                @if($order->order_status !== 'cancelled')
                                    <button 
                                        onclick="confirmCancel('{{ $order->order_number }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors"
                                        title="Cancel Order">
                                        <i class="fa-solid fa-ban mr-1"></i>
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-600 mb-1">No Orders Found</p>
                                <p class="text-sm text-gray-400">This user hasn't placed any orders yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination if needed -->
    @if($user->orders->count() > 0)
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <p>
                    Showing <span class="font-semibold">{{ $user->orders->count() }}</span> orders
                </p>
            </div>
        </div>
    @endif
</div>

<!-- Cancel Order Confirmation Modal -->
<script>
function confirmCancel(orderNumber) {
    if (confirm(`Are you sure you want to cancel order ${orderNumber}?`)) {
        // Add your cancel order logic here
        console.log('Cancelling order:', orderNumber);
    }
}
</script>

@endsection