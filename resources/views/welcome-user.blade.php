<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Online Store</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        <!-- Hero Section with User Welcome -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <div class="container mx-auto px-6 py-12">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-4xl font-bold mb-4">
                        Welcome back, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-xl opacity-90">
                        Continue exploring our latest products and exclusive offers.
                    </p>
                </div>
            </div>
        </div>
    
        <!-- Quick Actions -->
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 -mt-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Your Orders</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Track your recent orders and view order history.</p>
                    <a href="" class="text-blue-600 hover:text-blue-800 font-medium">
                        View Orders →
                    </a>
                </div>
    
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Wishlist</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Products you've saved for later.</p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">
                        View Wishlist →
                    </a>
                </div>
    
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Profile</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Manage your account settings and preferences.</p>
                    <a href="" class="text-blue-600 hover:text-blue-800 font-medium">
                        Edit Profile →
                    </a>
                </div>
            </div>
        </div>
    
        <!-- Recent Orders -->
        <div class="container mx-auto px-6 py-8">
            <h2 class="text-2xl font-bold mb-6">Recent Orders</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($recentOrders && count($recentOrders) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    #{{ $order->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${{ number_format($order->total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="" class="text-blue-600 hover:text-blue-900">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p>No orders found. Start shopping to see your orders here!</p>
                        <a href="" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Browse Products
                        </a>
                    </div>
                @endif
            </div>
        </div>
    
        <!-- Recommended Products -->
        <div class="container mx-auto px-6 py-8">
            <h2 class="text-2xl font-bold mb-6">Recommended for You</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($recommendedProducts ?? [] as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ $product->image }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">{{ $product->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-600 font-bold">${{ number_format($product->price, 2) }}</span>
                            <a href="" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
