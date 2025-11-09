@extends('layout.dashboard')

@section('avatar', auth()->user()->avatar)

@section("content")
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Breadcrumb -->
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('dashboard.orders') }}" class="hover:text-primary-600 transition-colors">
                    <i class="fa-solid fa-shopping-cart"></i>
                    Orders
                </a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Order Details</span>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a href="{{ route('dashboard.orders') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Orders
                </a>
                
                <!-- Print Invoice -->
                <a href="{{ route('orders.print', $order->id) }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-print"></i>
                    Print
                </a>    
            </div>
        </div>
        
        <!-- Page Title -->
        <div class="mt-4">
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="text-gray-600 mt-1">Order placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Summary Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Summary</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Order Information -->
                    <div class="space-y-4">
                        <h4 class="text-md font-semibold text-gray-900 border-b pb-2">Order Information</h4>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Order ID</span>
                            <span class="text-sm font-mono text-gray-900">#{{ $order->id }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Order Date</span>
                            <span class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Order Status</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $order->status === 'completed' ? 'bg-green-100 text-green-800 border border-green-200' : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                                <i class="fa-solid fa-circle mr-1 text-xs"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Payment Status</span>
                            <span class="text-sm text-gray-900 capitalize">{{ $order->payment_status ?? 'Pending' }}</span>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="space-y-4">
                        <h4 class="text-md font-semibold text-gray-900 border-b pb-2">Customer Information</h4>
                        
                        <div class="flex justify-between items-start py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Customer</span>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Phone</span>
                            <span class="text-sm text-gray-900">{{ $order->user->phone ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Total Orders</span>
                            <span class="text-sm text-gray-900">{{ $order->user->orders->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Items</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($order->products as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" 
                                                     src="{{ $product->image ? asset("storage/" . $product->image) : 'https://via.placeholder.com/100/cccccc/969696?text=No+Image' }}" 
                                                     alt="{{ $product->title }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                                                <div class="text-sm text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        ${{ number_format($product->pivot->price ?? $product->price, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $product->pivot->quantity ?? 1 }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-semibold text-gray-900">
                                        ${{ number_format(($product->pivot->price ?? $product->price) * ($product->pivot->quantity ?? 1), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        <i class="fa-solid fa-box-open text-2xl mb-2"></i>
                                        <p>No products found in this order</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Notes Card -->
            @if($order->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Notes</h2>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Order Summary & Actions -->
        <div class="space-y-6">
            <!-- Order Total Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Total</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Subtotal</span>
                        <span class="text-sm text-gray-900">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Shipping</span>
                        <span class="text-sm text-gray-900">${{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tax</span>
                        <span class="text-sm text-gray-900">${{ number_format($order->tax_amount ?? 0, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Actions</h2>
                
                <div class="space-y-3">
                    <!-- Status Update -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Update Status</label>
                        <select id="statusSelect" 
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <div class="flex justify-center items-center mt-4 gap-2">
                        <button onclick="updateOrderStatus()" 
                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="fa-solid fa-sync"></i>
                                Update Status
                            </button>
                            <button onclick="resendConfirmation()" 
                                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fa-solid fa-envelope"></i>
                                Resend Email
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->

                </div>
            </div>

            <!-- Shipping Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Shipping Address</label>
                        <p class="text-sm text-gray-900">
                            {{ $order->shipping_address ?? 'No shipping address provided' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Shipping Method</label>
                        <p class="text-sm text-gray-900">{{ $order->shipping_method ?? 'Standard Shipping' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tracking Number</label>
                        <p class="text-sm text-gray-900">{{ $order->tracking_number ?? 'Not available' }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-check text-green-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Order Placed</p>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($order->status === 'completed')
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-check text-green-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Order Completed</p>
                            <p class="text-sm text-gray-500">{{ $order->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function printInvoice() {
    }

    function updateOrderStatus() {
        const newStatus = document.getElementById('statusSelect').value;
        
        fetch('/dashboard/orders/${id}/update-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(data => {
            if (data.ok) {
                swal.fire('Success', 'Order status updated successfully', 'success');
                window.location.reload();
            } else {
                swal.fire('Error', 'Failed to update order status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            swal.fire('Error', 'An unexpected error occurred', 'error');
        });
    }

    function resendConfirmation() {
        swal.fire({
            title: 'Are you sure?',
            text: 'Resend order confirmation email to customer?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, resend it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/dashboard/orders/{{ $order->id }}/resend-confirmation', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal.fire('Sent!', 'Confirmation email sent successfully!', 'success');
                    } else {
                        swal.fire('Error', 'Error sending confirmation email', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush