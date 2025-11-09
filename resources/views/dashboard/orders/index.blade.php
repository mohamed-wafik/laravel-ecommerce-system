@extends('layout.dashboard')

@section('content')
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
                <p class="text-gray-600 mt-1">Manage customer orders and track order status</p>
            </div>

            <a href="{{ route('orders.export')}}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-file-export"></i> Export
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $countOrders }}</h3>
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
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $countOfOrderPending }}</h3>
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
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $countOfOrderCompleted }}</h3>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">PAYMENT STATUS</th>
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
                                        <div class="text-sm font-medium text-gray-900">#{{ $order["id"]}}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full" src="{{$order->user->avatar ?  asset('storage/' .  $order->user->avatar) : asset("storage/images/default_avatar.webp") }}" alt="{{ $order->user->name }}">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order["created_at"]->format('M j, Y') }}
                                <div class="text-gray-500 text-xs">{{ $order["created_at"]->format('g:i A') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ count($order->itemorders) }} items
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ${{ number_format($order["total_amount"], 2) }}
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$order["status"]] ?? 'bg-gray-100 text-gray-800' }}">
                                    <i class="fa-solid fa-circle mr-1 text-xs"></i> {{ ucfirst($order["status"]) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$order["payment_status"]] ?? 'bg-gray-100 text-gray-800' }}">
                                    <i class="fa-solid fa-circle mr-1 text-xs"></i> {{ ucfirst($order["payment_status"]) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route("orders.show", $order["id"])}}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="View">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <button 
                                        class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg"  
                                        title="Edit"
                                        onclick="modelEidtStatus({{ $order['id'] }})"
                                    >
                                        <i class="fa-solid fa-edit text-sm"></i>
                                    </button>
                                    <a href="{{ route("orders.print", $order['id']) }}" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg" title="Print">
                                        <i class="fa-solid fa-print text-sm"></i>
                                    </a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function printOrder(orderId) {

    }
    const modelEidtStatus = (id) => {
        Swal.fire({
            title: 'Edit Status',
            input: 'select',
            inputOptions: {
                'pending': 'Pending',
                'processing': 'Processing',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            },
            inputPlaceholder: 'Select status',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedStatus = result.value;
                fetch(`/dashboard/orders/${id}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: selectedStatus })
                })
                .then(data => {
                    if (data.ok) {
                        Swal.fire('Updated!', 'Order status has been updated.', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', 'There was an error updating the status.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush