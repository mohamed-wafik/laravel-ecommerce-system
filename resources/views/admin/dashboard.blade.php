@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section("content")
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Users Card -->
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $countUsers }}</h3>
                    <div class="flex items-center mt-3">
                        <span class="text-sm text-success font-medium">+12%</span>
                        <span class="text-sm text-gray-500 ml-2">from last week</span>
                    </div>
                </div>
                <div class="p-4 bg-primary-50 rounded-xl group-hover:bg-primary-100 transition-colors">
                    <i class="fas fa-users text-2xl text-primary-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary-600 h-2 rounded-full" style="width: 75%"></div>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $countProducts }}</h3>
                    <div class="flex items-center mt-3">
                        <span class="text-sm text-success font-medium">+8%</span>
                        <span class="text-sm text-gray-500 ml-2">from last week</span>
                    </div>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-box text-2xl text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Categories</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $countCategories }}</h3>
                    <div class="flex items-center mt-3">
                        <span class="text-sm text-success font-medium">+5%</span>
                        <span class="text-sm text-gray-500 ml-2">from last week</span>
                    </div>
                </div>
                <div class="p-4 bg-green-50 rounded-xl group-hover:bg-green-100 transition-colors">
                    <i class="fas fa-tags text-2xl text-green-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 50%"></div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $countOrders }}</h3>
                    <div class="flex items-center mt-3">
                        <span class="text-sm text-success font-medium">+23%</span>
                        <span class="text-sm text-gray-500 ml-2">from last week</span>
                    </div>
                </div>
                <div class="p-4 bg-amber-50 rounded-xl group-hover:bg-amber-100 transition-colors">
                    <i class="fas fa-shopping-cart text-2xl text-amber-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-amber-600 h-2 rounded-full" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Sales Overview</h3>
                <div class="flex space-x-2">
                    <button id="btn-salesChart7Days"
                        class="px-3 py-1 text-sm rounded-lg font-medium transition-colors bg-primary-50 text-primary-600">
                        7 Days
                    </button>
                    <button id="btn-salesChart30Days"
                        class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 rounded-lg font-medium transition-colors">
                        30 Days
                    </button>
                </div>
            </div>
            <div class="relative">
                <div id="salesChart7Days">
                    <canvas height="300"></canvas>
                </div>
                <div  id="salesChart30Days" class="hidden" >

                    <canvas height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Orders Status Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Orders Status</h3>
            <canvas id="ordersStatusChart" height="300"></canvas>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order["id"] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">User {{ $order["user_id"] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($order["total_amount"], 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($order["status"] == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($order["status"] == 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($order["status"] == 'processing')
                                        <span class="badge badge-info">Processing</span>
                                    @else
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Low Stock Products</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($lowStockProducts as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product["title"] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $product["stock"] }}</td>
                                <td class="px-6 py-4">
                                    @if($product["stock"] <= 2)
                                        <span class="badge badge-danger">Critical</span>
                                    @else
                                        <span class="badge badge-warning">Low Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bottom Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Top Country -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Country</h3>
            <div class="flex items-center justify-center py-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe-americas text-primary-600 text-xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ $topCountrySales->country ?? 'N/A' }}</h4>
                    <p class="text-gray-500 mt-2">Top Sales Country</p>
                    <p class="text-sm text-gray-400">{{ $topCountrySales->total_sales ?? 0 }} orders</p>
                </div>
            </div>
        </div>

        <!-- New Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">New Users This Week</h3>
            <div class="flex items-center justify-between mb-4">
                <span class="text-3xl font-bold text-gray-900">{{ $usersWeekEnd }}</span>
                <span class="text-sm text-success bg-green-50 px-2 py-1 rounded-full">+12%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-green-500 h-3 rounded-full"
                     style="width: {{ min(100, ($usersWeekEnd / max(1, $countUsers)) * 100) }}%">
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-3">{{ $usersWeekEnd }} out of {{ $countUsers }} total users</p>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button class="w-full flex items-center justify-between p-4 bg-primary-50 hover:bg-primary-100 rounded-xl transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-plus text-primary-600 text-lg"></i>
                        <span class="ml-3 font-medium text-gray-900">Add New Product</span>
                    </div>
                    <i class="fas fa-chevron-right text-primary-600"></i>
                </button>
                <button class="w-full flex items-center justify-between p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line text-green-600 text-lg"></i>
                        <span class="ml-3 font-medium text-gray-900">View Reports</span>
                    </div>
                    <i class="fas fa-chevron-right text-green-600"></i>
                </button>
                <button class="w-full flex items-center justify-between p-4 bg-amber-50 hover:bg-amber-100 rounded-xl transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-cog text-amber-600 text-lg"></i>
                        <span class="ml-3 font-medium text-gray-900">Settings</span>
                    </div>
                    <i class="fas fa-chevron-right text-amber-600"></i>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const sales7DaysCtx = document.querySelector('#salesChart7Days canvas').getContext('2d');
    const sales7DaysChart = new Chart(sales7DaysCtx, {
        type: 'line',
        data: {
            labels: @json($salesLast7Days->pluck('date')),
            datasets: [{
                label: 'Sales',
                data: @json($salesLast7Days->pluck('total_sales')),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { drawBorder: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // ----- SALES (30 DAYS) -----
    const sales30DaysCtx = document.querySelector('#salesChart30Days canvas').getContext('2d');
    const sales30DaysChart = new Chart(sales30DaysCtx, {
        type: 'line',
        data: {
            labels: @json($salesLast30Days->pluck('date')),
            datasets: [{
                label: 'Sales (30 Days)',
                data: @json($salesLast30Days->pluck('total_sales')),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { drawBorder: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // ----- TOGGLE BUTTON HANDLER -----
    const btn7 = document.getElementById('btn-salesChart7Days');
    const btn30 = document.getElementById('btn-salesChart30Days');
    const chart7 = document.getElementById('salesChart7Days');
    const chart30 = document.getElementById('salesChart30Days');

    btn7.addEventListener('click', () => {
        chart7.classList.remove('hidden');
        chart30.classList.add('hidden');
        btn7.classList.add('bg-primary-50', 'text-primary-600');
        btn7.classList.remove('text-gray-500');
        btn30.classList.remove('bg-primary-50', 'text-primary-600');
        btn30.classList.add('text-gray-500');
    });

    btn30.addEventListener('click', () => {
        chart30.classList.remove('hidden');
        chart7.classList.add('hidden');
        btn30.classList.add('bg-primary-50', 'text-primary-600');
        btn30.classList.remove('text-gray-500');
        btn7.classList.remove('bg-primary-50', 'text-primary-600');
        btn7.classList.add('text-gray-500');
    });

    // ----- ORDERS STATUS -----
    const ordersStatusCtx = document.getElementById('ordersStatusChart').getContext('2d');
    new Chart(ordersStatusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusOfOrders->pluck('status')),
            datasets: [{
                data: @json($statusOfOrders->pluck('total')),
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
