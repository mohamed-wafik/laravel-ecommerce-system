@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section('content')
<!-- Header -->
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Category Details</h1>
            <p class="text-gray-600 mt-1">View category information and associated products</p>
        </div>

        <a href="{{ route('categories.index') }}"
           class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Categories
        </a>
    </div>
</div>

<!-- CATEGORY INFO CARD -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center flex-col md:flex-row gap-6">
        <!-- Image -->
        <div class="flex-shrink-0">
            <img class="h-32 w-32 rounded-xl border-4 border-gray-200 object-cover"
                 src="{{ $category["image"] ? asset('storage/' . $category["image"]) : 'https://via.placeholder.com/150/cccccc/969696?text=No+Image' }}"
                 alt="{{ $category["title"] }}">
        </div>

        <!-- Info -->
        <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ $category["title"] }}</h2>
            <p class="text-gray-600 mb-3">Category ID: #{{ $category["id"] }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <p class="text-gray-500">Created At : </p>
                    <p class="text-gray-900 font-medium">{{ $category["created_at"]->format('M d, Y') }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-gray-500">Description : </p>
                    <p class="text-gray-900 font-medium">{{ $category["description"] ?? 'No description available.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRODUCTS TABLE -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Products in this Category</h3>
        <span class="text-sm text-gray-500">{{ $category->products->count() }} total</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead>
                <tr class="bg-gradient-to-r from-primary-600 to-primary-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($category->products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/50/cccccc/969696?text=No+Image' }}"
                                 class="w-10 h-10 rounded-md border border-gray-200 object-cover" 
                                 alt="{{ $product->title }}">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->title }}</p>
                                <p class="text-xs text-gray-500">#{{ $product->id }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->stock ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $product->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('products.show', $product->id) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                            <p class="text-lg font-medium">No Products Found</p>
                            <p class="text-sm">This category doesn't have any products yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
