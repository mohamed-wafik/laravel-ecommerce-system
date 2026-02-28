@extends('layout.dashboard')

@section('avatar', auth()->user()->avatar)

@section("content")
    <!-- Header Section -->
    <div class="mb-8">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('dashboard.orders') }}" class="hover:text-primary-600 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-semibold">Order Details</span>
        </div>
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $order->order_number }}</h1>
                    @php
                        $statusConfig = [
                            'pending' => [
                                'bg' => 'bg-amber-100',
                                'text' => 'text-amber-800',
                                'border' => 'border-amber-300',
                                'icon' => 'fa-clock',
                                'iconColor' => 'text-amber-600'
                            ],
                            'processing' => [
                                'bg' => 'bg-blue-100',
                                'text' => 'text-blue-800',
                                'border' => 'border-blue-300',
                                'icon' => 'fa-spinner',
                                'iconColor' => 'text-blue-600'
                            ],
                            'shipped' => [
                                'bg' => 'bg-purple-100',
                                'text' => 'text-purple-800',
                                'border' => 'border-purple-300',
                                'icon' => 'fa-truck',
                                'iconColor' => 'text-purple-600'
                            ],
                            'delivered' => [
                                'bg' => 'bg-emerald-100',
                                'text' => 'text-emerald-800',
                                'border' => 'border-emerald-300',
                                'icon' => 'fa-check-circle',
                                'iconColor' => 'text-emerald-600'
                            ],
                            'cancelled' => [
                                'bg' => 'bg-red-100',
                                'text' => 'text-red-800',
                                'border' => 'border-red-300',
                                'icon' => 'fa-times-circle',
                                'iconColor' => 'text-red-600'
                            ]
                        ];
                        $config = $statusConfig[$order->order_status] ?? [
                            'bg' => 'bg-gray-100',
                            'text' => 'text-gray-800',
                            'border' => 'border-gray-300',
                            'icon' => 'fa-circle',
                            'iconColor' => 'text-gray-600'
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border-2 {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                        <i class="fa-solid {{ $config['icon'] }} {{ $config['iconColor'] }}"></i>
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
                <p class="text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-calendar text-sm"></i>
                    <span>Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</span>
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.orders') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back</span>
                </a>
                
                <a href="{{ route('orders.print', $order->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl hover:from-purple-700 hover:to-purple-800 shadow-lg shadow-purple-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-purple-500/40 hover:-translate-y-0.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Invoice</span>
                </a>    
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Summary Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Order Summary
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Order Information -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="fa-solid fa-info-circle text-blue-600"></i>
                                </div>
                                <h4 class="text-base font-bold text-gray-900">Order Information</h4>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Order Number</span>
                                    <span class="text-sm font-bold font-mono text-gray-900 bg-gray-100 px-2.5 py-1 rounded">{{ $order->order_number }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Order Date</span>
                                    <span class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Order Status</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border-2 {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                        <i class="fa-solid {{ $config['icon'] }} {{ $config['iconColor'] }} text-[10px]"></i>
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </div>
                                
                                {{-- NEW: Payment Status --}}
                                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Payment Status</span>
                                    @php
                                        $paymentConfig = [
                                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => 'fa-clock'],
                                            'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => 'fa-check-circle'],
                                            'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => 'fa-times-circle'],
                                        ];
                                        $payConfig = $paymentConfig[$order->payment_status] ?? $paymentConfig['pending'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border-2 {{ $payConfig['bg'] }} {{ $payConfig['text'] }} {{ $payConfig['border'] }}">
                                        <i class="fa-solid {{ $payConfig['icon'] }} text-[10px]"></i>
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>

                                {{-- NEW: Payment Method --}}
                                <div class="flex justify-between items-center py-2.5">
                                    <span class="text-sm font-medium text-gray-600">Payment Method</span>
                                    <span class="inline-flex items-center gap-1.5 text-sm text-gray-900">
                                        @if($order->payment_method === 'card')
                                            <i class="fa-solid fa-credit-card text-blue-600"></i>
                                            <span>Credit Card</span>
                                        @elseif($order->payment_method === 'cod')
                                            <i class="fa-solid fa-money-bill text-green-600"></i>
                                            <span>Cash on Delivery</span>
                                        @elseif($order->payment_method === 'wallet')
                                            <i class="fa-solid fa-wallet text-purple-600"></i>
                                            <span>Mobile Wallet</span>
                                        @else
                                            <span>{{ ucfirst($order->payment_method) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i class="fa-solid fa-user text-purple-600"></i>
                                </div>
                                <h4 class="text-base font-bold text-gray-900">Customer Information</h4>
                            </div>
                            
                            <div class="space-y-3">
                                {{-- Customer Info from order --}}
                                <div class="py-2.5 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600 block mb-2">Customer</span>
                                    <div class="flex items-center gap-3">
                                        @if($order->user)
                                            <img class="h-10 w-10 rounded-full ring-2 ring-gray-200 object-cover" 
                                                 src="{{ $order->user->avatar ?? asset('storage/images/default_avatar.webp') }}" 
                                                 alt="{{ $order->user->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'Default User') }}&background=random'"
                                            >

                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $order->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                            </div>
                                        @else
                                            <div class="h-10 w-10 rounded-full ring-2 ring-gray-200 bg-gray-200 flex items-center justify-center">
                                                <i class="fa-solid fa-user text-gray-400"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Phone from order --}}
                                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Phone</span>
                                    <span class="text-sm text-gray-900">{{ $order->customer_phone ?? 'N/A' }}</span>
                                </div>
                                
                                {{-- City --}}
                                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">City</span>
                                    <span class="text-sm text-gray-900">{{ $order->city ?? 'N/A' }}</span>
                                </div>
                                
                                @if($order->user)
                                <div class="flex justify-between items-center py-2.5">
                                    <span class="text-sm font-medium text-gray-600">Total Orders</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-800 rounded-lg text-xs font-bold">
                                        <i class="fa-solid fa-shopping-bag text-[10px]"></i>
                                        {{ $order->user->orders->count() }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-box-open"></i>
                        {{-- Order Items --}}
                        Order Items ({{ $order->items->count() }})
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Each item with product details --}}
                            @forelse($order->items as $item)
                                <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                <img class="h-16 w-16 rounded-xl object-cover border-2 border-gray-200 shadow-sm" 
                                                     src="{{ $item->product->image ?? 'https://via.placeholder.com/100/cccccc/969696?text=No+Image' }}" 
                                                     alt="{{ $item->product->title }}"
                                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->product->title) }}&background=random'"
                                                >
                                                <div class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                                    {{ $item->quantity }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 mb-1">{{ $item->product->title }}</div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-gray-500">SKU:</span>
                                                    <span class="text-xs font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $item->product->sku ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Unit Price --}}
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-gray-900">${{ number_format($item->price, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg">
                                            <i class="fa-solid fa-times text-xs text-gray-500"></i>
                                            <span class="text-sm font-bold text-gray-900">{{ $item->quantity }}</span>
                                        </div>
                                    </td>
                                    {{-- Line Total --}}
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-base font-bold text-gray-900">${{ number_format($item->total, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="p-4 bg-gray-100 rounded-full">
                                                <i class="fa-solid fa-box-open text-4xl text-gray-400"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-base font-semibold text-gray-900">No products found</h3>
                                                <p class="text-sm text-gray-500 mt-1">This order doesn't contain any products.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column - Summary & Actions -->
        <div class="space-y-6">
            <!-- Order Total Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4 border-b border-emerald-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-dollar-sign"></i>
                        Order Total
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                    {{-- Order Pricing Breakdown --}}
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Subtotal</span>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Shipping</span>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-600">Tax</span>
                        <span class="text-sm font-semibold text-gray-900">${{ number_format($order->tax, 2) }}</span>
                    </div>

                    @if($order->discount > 0)
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-sm font-medium text-green-600">Discount</span>
                        <span class="text-sm font-semibold text-green-600">-${{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        {{-- Grand Total --}}
                        <span class="text-2xl font-bold text-emerald-600">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Actions Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 border-b border-blue-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-sliders"></i>
                        Order Actions
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Update Status</label>
                            <select id="statusSelect" 
                                    class="w-full rounded-xl border-2 border-gray-300 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-primary-600 focus:ring-4 focus:ring-primary-600/20 transition-all">
                                <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>⏱️ Pending</option>
                                <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                                <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                            </select>
                        </div>
                        
                        <button onclick="updateOrderStatus({{ $order->id }})" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5">
                            <i class="fa-solid fa-sync"></i>
                            Update Status
                        </button>
                        
                        <button onclick="resendConfirmation({{ $order->id }})" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-blue-700 bg-blue-100 rounded-xl hover:bg-blue-200 border-2 border-blue-200 hover:border-blue-300 transition-all duration-200">
                            <i class="fa-solid fa-envelope"></i>
                            Resend Confirmation
                        </button>
                    </div>
                </div>
            </div>

            <!-- Shipping Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 border-b border-purple-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-truck"></i>
                        Shipping Information
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-2">
                            <i class="fa-solid fa-map-marker-alt text-purple-600"></i>
                            Shipping Address
                        </label>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            {{ $order->shipping_address }}
                            @if($order->city)
                                <br>{{ $order->city }}
                                @if($order->postal_code), {{ $order->postal_code }}@endif
                            @endif
                        </p>
                    </div>
                    
                    {{-- ✅ NEW: Shipping Method --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-2">
                            <i class="fa-solid fa-shipping-fast text-purple-600"></i>
                            Shipping Method
                        </label>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            {{ ucfirst($order->shipping_method) }}
                            @if($order->shipping_method === 'standard')
                                (3-5 days)
                            @elseif($order->shipping_method === 'express')
                                (1-2 days)
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-2">
                            <i class="fa-solid fa-barcode text-purple-600"></i>
                            Tracking Number
                        </label>
                        @if($order->tracking_number)
                            <p class="text-sm font-mono font-bold text-gray-900 bg-purple-50 p-3 rounded-lg border-2 border-purple-200">
                                {{ $order->tracking_number }}
                            </p>
                        @else
                            <p class="text-sm text-gray-500 bg-gray-50 p-3 rounded-lg border border-gray-200 italic">
                                Not available yet
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Order Timeline
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Order Placed -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                    <i class="fa-solid fa-check text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-sm font-bold text-gray-900">Order Placed</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    {{ $order->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        
                        @if($order->order_status === 'processing')
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30">
                                    <i class="fa-solid fa-spinner fa-spin text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-sm font-bold text-gray-900">Order Processing</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    {{ $order->updated_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if(in_array($order->order_status, ['shipped', 'delivered']))
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg shadow-purple-500/30">
                                    <i class="fa-solid fa-truck text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-sm font-bold text-gray-900">Order Shipped</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    {{ $order->updated_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->order_status === 'delivered')
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                    <i class="fa-solid fa-circle-check text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-sm font-bold text-gray-900">Order Delivered</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    {{ $order->updated_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($order->order_status === 'cancelled')
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg shadow-red-500/30">
                                    <i class="fa-solid fa-times-circle text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="text-sm font-bold text-gray-900">Order Cancelled</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    {{ $order->updated_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    function updateOrderStatus(orderId) {
        const newStatus = document.getElementById('statusSelect').value;
        
        Swal.fire({
            title: '<span style="font-size: 1.5rem; font-weight: 700;">Confirm Status Update</span>',
            html: '<p style="color: #6b7280; margin-top: 0.5rem;">Are you sure you want to change the order status to <strong>' + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + '</strong>?</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-check mr-2"></i>Yes, Update',
            cancelButtonText: '<i class="fa-solid fa-times mr-2"></i>Cancel',
            customClass: {
                confirmButton: 'swal2-confirm-custom',
                cancelButton: 'swal2-cancel-custom',
                popup: 'swal2-popup-custom'
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Updating...',
                    html: 'Please wait while we update the order status.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/dashboard/orders/${orderId}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated!',
                            text: 'Order status has been successfully updated.',
                            confirmButtonText: 'Got it!',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error('Update failed');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'There was an error updating the status. Please try again.',
                    });
                });
            }
        });
    }

    function resendConfirmation(orderId) {
        Swal.fire({
            title: 'Resend Confirmation?',
            text: 'Send order confirmation email to the customer?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-paper-plane mr-2"></i>Yes, Send',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                // Add your resend logic here
                Swal.fire('Sent!', 'Confirmation email sent successfully!', 'success');
            }
        });
    }
</script>
@endpush