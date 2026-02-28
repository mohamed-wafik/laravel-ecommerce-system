@extends('layout.dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Orders Management</h1>
                <p class="text-gray-600 mt-2">Manage and track all customer orders in real-time</p>
            </div>

            <div class="flex items-center gap-3">
                @if(Route::has('orders.export'))
                    <a href="{{ route('orders.export') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl hover:from-primary-700 hover:to-primary-800 shadow-lg shadow-primary-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-primary-500/40 hover:-translate-y-0.5">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Export Orders</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Orders -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl shadow-sm border border-blue-200/50 p-6 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Total Orders</p>
                    <h3 class="text-3xl font-bold text-blue-900 mt-3">{{ number_format($countOrders ?? 0) }}</h3>
                    <p class="text-xs text-blue-600 mt-2">All time</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg">
                    <i class="fa-solid fa-shopping-cart text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl shadow-sm border border-amber-200/50 p-6 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Pending</p>
                    <h3 class="text-3xl font-bold text-amber-900 mt-3">{{ number_format($countOfOrderPending ?? 0) }}</h3>
                    <p class="text-xs text-amber-600 mt-2">Awaiting action</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg">
                    <i class="fa-solid fa-clock text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl shadow-sm border border-emerald-200/50 p-6 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Completed</p>
                    <h3 class="text-3xl font-bold text-emerald-900 mt-3">{{ number_format($countOfOrderCompleted ?? 0) }}</h3>
                    <p class="text-xs text-emerald-600 mt-2">Successfully fulfilled</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg">
                    <i class="fa-solid fa-check-circle text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl shadow-sm border border-purple-200/50 p-6 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Total Revenue</p>
                    <h3 class="text-3xl font-bold text-purple-900 mt-3">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                    <p class="text-xs text-purple-600 mt-2">Gross earnings</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg">
                    <i class="fa-solid fa-dollar-sign text-2xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Recent Orders</h2>
            <p class="text-sm text-gray-600 mt-1">View and manage all customer orders</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-800 to-gray-900 border-b border-gray-700">
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Items</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders ?? [] as $order)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                            <!-- Order ID -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 rounded-lg group-hover:bg-primary-100 transition-colors">
                                        <i class="fa-solid fa-receipt text-gray-500 group-hover:text-primary-600 transition-colors"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">#{{ str_pad($order->id ?? 0, 6, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $order->order_number ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @if(isset($order->user))
                                        <div class="relative">
                                            <img class="h-10 w-10 rounded-full ring-2 ring-gray-200 object-cover" 
                                                 src="{{ $order->user->avatar ?? asset('storage/images/default_avatar.webp') }}" 
                                                 alt="{{ $order->user->name ?? 'Customer' }}"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'Customer') }}&background=random'"
                                            >
                                            <div class="absolute -bottom-1 -right-1 h-3.5 w-3.5 bg-emerald-500 rounded-full ring-2 ring-white"></div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->user->email ?? 'N/A' }}</div>
                                        </div>
                                    @else
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full ring-2 ring-gray-200 bg-gray-200 flex items-center justify-center">
                                                <i class="fa-solid fa-user text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $order->customer_name ?? 'Guest' }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->customer_email ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Date & Time -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if(isset($order->created_at))
                                    <div class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-clock text-[10px]"></i>
                                        {{ $order->created_at->format('g:i A') }}
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">N/A</div>
                                @endif
                            </td>

                            <!-- Items -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @php
                                    $itemCount = isset($order->items) ? $order->items->count() : 0;
                                @endphp
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg">
                                    <i class="fa-solid fa-box text-xs text-gray-600"></i>
                                    <span class="text-sm font-semibold text-gray-900">{{ $itemCount }}</span>
                                    <span class="text-xs text-gray-600">{{ $itemCount == 1 ? 'item' : 'items' }}</span>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-base font-bold text-gray-900">${{ number_format($order->total ?? 0, 2) }}</div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-5 whitespace-nowrap">
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
                                        'completed' => [
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
                                    $orderStatus = $order->order_status ?? 'pending';
                                    $config = $statusConfig[$orderStatus] ?? $statusConfig['pending'];
                                @endphp
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border-2 {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                    <i class="fa-solid {{ $config['icon'] }} {{ $config['iconColor'] }}"></i>
                                    {{ ucfirst($orderStatus) }}
                                </span>
                            </td>

                            <!-- Payment Status -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @php
                                    $paymentStatus = $order->payment_status ?? 'pending';
                                    $paymentConfig = [
                                        'paid' => [
                                            'bg' => 'bg-emerald-100',
                                            'text' => 'text-emerald-800',
                                            'border' => 'border-emerald-300',
                                            'icon' => 'fa-check-circle',
                                            'iconColor' => 'text-emerald-600'
                                        ],
                                        'pending' => [
                                            'bg' => 'bg-amber-100',
                                            'text' => 'text-amber-800',
                                            'border' => 'border-amber-300',
                                            'icon' => 'fa-clock',
                                            'iconColor' => 'text-amber-600'
                                        ],
                                        'failed' => [
                                            'bg' => 'bg-red-100',
                                            'text' => 'text-red-800',
                                            'border' => 'border-red-300',
                                            'icon' => 'fa-times-circle',
                                            'iconColor' => 'text-red-600'
                                        ],
                                        'refunded' => [
                                            'bg' => 'bg-purple-100',
                                            'text' => 'text-purple-800',
                                            'border' => 'border-purple-300',
                                            'icon' => 'fa-undo',
                                            'iconColor' => 'text-purple-600'
                                        ]
                                    ];
                                    $pConfig = $paymentConfig[$paymentStatus] ?? $paymentConfig['pending'];
                                @endphp
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border-2 {{ $pConfig['bg'] }} {{ $pConfig['text'] }} {{ $pConfig['border'] }}">
                                    <i class="fa-solid {{ $pConfig['icon'] }} {{ $pConfig['iconColor'] }}"></i>
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(Route::has('orders.show'))
                                        <a href="{{ route('orders.show', $order->id) }}" 
                                           class="group/btn relative p-2.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 hover:shadow-md" 
                                           title="View Details">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                            <span class="absolute -top-8 right-0 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">View</span>
                                        </a>
                                    @endif
                                    <button 
                                        class="group/btn relative p-2.5 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 hover:shadow-md"  
                                        title="Edit Status"
                                        onclick="modelEditStatus({{ $order->id }}, '{{ $order->order_status ?? 'pending' }}')"
                                    >
                                        <i class="fa-solid fa-edit text-sm"></i>
                                        <span class="absolute -top-8 right-0 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Edit</span>
                                    </button>
                                    @if(Route::has('orders.print'))
                                        <a href="{{ route('orders.print', $order->id) }}" 
                                           class="group/btn relative p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-all duration-200 hover:shadow-md" 
                                           title="Print">
                                            <i class="fa-solid fa-print text-sm"></i>
                                            <span class="absolute -top-8 right-0 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Print</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="p-6 bg-gray-100 rounded-full">
                                        <i class="fa-solid fa-inbox text-5xl text-gray-400"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">No orders found</h3>
                                        <p class="text-sm text-gray-500 mt-1">There are no orders to display at the moment.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-semibold text-gray-900">{{ isset($orders) ? $orders->count() : 0 }}</span> orders
                </div>
                <div class="text-sm font-bold text-gray-900">
                    Total: <span class="text-primary-600">{{ number_format($countOrders ?? 0) }}</span> orders
                </div>
            </div>
        </div>

        @if($orders->hasPages())
            @include('components.Pagination', [
                'firstItem' => $orders->firstItem(),
                'lastItem' => $orders->lastItem(),
                'total' => $orders->total(),
                'previousPageUrl' => $orders->previousPageUrl(),
                'nextPageUrl' => $orders->nextPageUrl(),
                'onFirstPage' => $orders->onFirstPage(),
                'hasMorePages' => $orders->hasMorePages()
            ])
        @endif
    </div>
@endsection

@push('script')
<script>
    const modelEditStatus = (id, currentStatus) => {
        Swal.fire({
            title: '<span style="font-size: 1.5rem; font-weight: 700;">Update Order Status</span>',
            html: '<p style="color: #6b7280; margin-top: 0.5rem;">Change the status for Order #' + String(id).padStart(6, '0') + '</p>',
            input: 'select',
            inputOptions: {
                'pending': 'Pending',
                'processing': 'Processing',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            },
            inputValue: currentStatus,
            inputPlaceholder: 'Select new status',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-check mr-2"></i>Update Status',
            cancelButtonText: '<i class="fa-solid fa-times mr-2"></i>Cancel',
            customClass: {
                confirmButton: 'swal2-confirm-custom',
                cancelButton: 'swal2-cancel-custom',
                popup: 'swal2-popup-custom'
            },
            buttonsStyling: false,
            didOpen: () => {
                const style = document.createElement('style');
                style.textContent = `
                    .swal2-popup-custom {
                        border-radius: 1rem;
                        padding: 2rem;
                    }
                    .swal2-confirm-custom {
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
                        color: white !important;
                        padding: 0.75rem 1.5rem !important;
                        border-radius: 0.75rem !important;
                        font-weight: 600 !important;
                        margin: 0.5rem !important;
                        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3) !important;
                        transition: all 0.2s !important;
                    }
                    .swal2-confirm-custom:hover {
                        transform: translateY(-2px) !important;
                        box-shadow: 0 6px 8px -1px rgba(16, 185, 129, 0.4) !important;
                    }
                    .swal2-cancel-custom {
                        background: #f3f4f6 !important;
                        color: #374151 !important;
                        padding: 0.75rem 1.5rem !important;
                        border-radius: 0.75rem !important;
                        font-weight: 600 !important;
                        margin: 0.5rem !important;
                        transition: all 0.2s !important;
                    }
                    .swal2-cancel-custom:hover {
                        background: #e5e7eb !important;
                    }
                    .swal2-select {
                        border-radius: 0.75rem !important;
                        border: 2px solid #e5e7eb !important;
                        padding: 0.75rem 1rem !important;
                        font-size: 0.95rem !important;
                        margin-top: 1rem !important;
                    }
                    .swal2-select:focus {
                        border-color: #10b981 !important;
                        outline: none !important;
                        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
                    }
                `;
                document.head.appendChild(style);
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const selectedStatus = result.value;
                
                // Show loading state
                Swal.fire({
                    title: 'Updating...',
                    html: 'Please wait while we update the order status.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/dashboard/orders/${id}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_status : selectedStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '<span style="font-size: 1.5rem; font-weight: 700;">Status Updated!</span>',
                            html: '<p style="color: #6b7280; margin-top: 0.5rem;">Order status has been successfully updated.</p>',
                            confirmButtonText: 'Got it!',
                            customClass: {
                                confirmButton: 'swal2-confirm-custom',
                                popup: 'swal2-popup-custom'
                            },
                            buttonsStyling: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="font-size: 1.5rem; font-weight: 700;">Update Failed</span>',
                        html: '<p style="color: #6b7280; margin-top: 0.5rem;">There was an error updating the status. Please try again.</p>',
                        confirmButtonText: 'Close',
                        customClass: {
                            confirmButton: 'swal2-cancel-custom',
                            popup: 'swal2-popup-custom'
                        },
                        buttonsStyling: false
                    });
                });
            }
        });
    }
</script>
@endpush