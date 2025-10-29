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
                 src="{{ $user["avatar"] ? asset('storage/'.$user["avatar"]) : 'https://via.placeholder.com/150/cccccc/969696?text=No+Image' }}"
                 alt="{{ $user["name"] }}">
        </div>

        <!-- Info -->
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ $user["name"] }}</h2>
            <p class="text-gray-600 mb-3">User ID: #{{ $user["id"] }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center gap-1.5">
                    <p class="text-gray-500">Email : </p>
                    <p class="text-gray-900 font-medium">{{ $user["email"] }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <p class="text-gray-500">Phone : </p>
                    <p class="text-gray-900 font-medium">{{ $user["phone "]?? 'N/A' }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <p class="text-gray-500">Role : </p>
                    @php
                        $roleColors = [
                            'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'user' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'moderator' => 'bg-green-100 text-green-800 border-green-200'
                        ];
                        $roleColor = $roleColors[$user["role"]] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $roleColor }}">
                        <i class="fa-solid fa-user-shield mr-1 text-xs"></i>
                        {{ ucfirst($user["role"]) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <p class="text-gray-500">Joined : </p>
                    <p class="text-gray-900 font-medium">{{ $user["created_at"]->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ORDERS TABLE -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Orders</h3>
        <span class="text-sm text-gray-500">{{ $user->orders->count() }} total</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead>
                <tr class="bg-gradient-to-r from-primary-600 to-primary-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($user->orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border 
                                {{ match($order->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                } }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                            <p class="text-lg font-medium">No Orders Found</p>
                            <p class="text-sm">This user hasn't placed any orders yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
