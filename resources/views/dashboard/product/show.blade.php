@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section("content")
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Breadcrumb -->
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('products.index') }}" class="hover:text-cyan-600 transition-colors">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    Products
                </a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Product Details</span>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a href="{{ route('products.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
                
                <!-- Edit Button -->
                <a href="{{ route('products.edit', $product['id']) }}" 
                   class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 transition-colors">
                    <i class="fa-solid fa-edit"></i>
                    Edit Product
                </a>
            </div>
        </div>
        
        <!-- Page Title -->
        <div class="mt-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ $product["title"] }}</h1>
            <p class="text-gray-600 mt-1">Product ID: #{{ $product["id"] }}</p>
        </div>
    </div>

    <!-- Product Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Product Images & Basic Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Images</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Main Image -->
                    <div class="col-span-1">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden">
                            <img 
                                 src="{{ asset('storage/' . $product['image']) }}" 
                                 alt="{{ $product["title"] }}" 
                                 class="w-full h-64 object-cover rounded-lg shadow-sm"
                                 onerror="this.src='https://via.placeholder.com/400x400/f3f4f6/9ca3af?text=No+Image'">
                        </div>
                    </div>
                    
                    <!-- Additional Images -->
                    <div class="col-span-1">
                        <div class="grid grid-cols-2 gap-3">
                            @for($i = 0; $i < 3; $i++)
                                <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden">
                                    <img 
                                         src="{{ asset('storage/' . $product['image']) }}" 
                                         alt="{{ $product["title"] }} - Image {{ $i + 1 }}" 
                                         class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                                         onerror="this.src='https://via.placeholder.com/200x200/f3f4f6/9ca3af?text=Image+{{ $i + 1 }}'">
                                </div>
                            @endfor
                            <div class="aspect-w-1 aspect-h-1 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="text-center">
                                    <i class="fa-solid fa-plus text-gray-400 text-xl mb-2"></i>
                                    <p class="text-xs text-gray-500">Add More</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">
                        {{ $product["description"] ?? 'No description available for this product.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column - Product Info & Actions -->
        <div class="space-y-6">
            <!-- Product Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Summary</h2>
                
                <!-- Price -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-600">Price</label>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-bold text-gray-900">${{ number_format($product["price"], 2) }}</span>
                        @if($product->compare_price)
                            <span class="text-lg text-gray-500 line-through">${{ number_format($product["discoumt"], 2) }}</span>
                            <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                                {{ number_format((($product["discount"] - $product["price"]) / $product["compare_price"]) * 100, 0) }}% OFF
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-600">Stock Status</label>
                    <div class="mt-1">
                        @if($product["stock"]> 10)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <i class="fa-solid fa-check-circle mr-2"></i>
                                In Stock ({{ $product["stock "] }})
                            </span>
                        @elseif($product["stock"] > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                                Low Stock ({{ $product["stock"] }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                <i class="fa-solid fa-times-circle mr-2"></i>
                                Out of Stock
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-600">Category</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800 border border-cyan-200">
                            <i class="fa-solid fa-tag mr-2"></i>
                            {{ $product->category["title"] ?? 'Uncategorized' }}
                        </span>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="text-sm font-medium text-gray-600">Status</label>
                    <div class="mt-1">
                        @if($product->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <i class="fa-solid fa-circle-check mr-2"></i>
                                Active
                            </span>
                        @elseif($product->status === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                <i class="fa-solid fa-pen mr-2"></i>
                                Draft
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                <i class="fa-solid fa-ban mr-2"></i>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="space-y-3">
                    <button onclick="toggleStockAlert()" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fa-solid fa-bell"></i>
                        Stock Alert
                    </button>
                    
                    <button onclick="exportProductData()" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fa-solid fa-download"></i>
                        Export Data
                    </button>
                </div>
            </div>

            <!-- Sales & Analytics -->

        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">
         <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 w-full">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Sales Analytics</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Total Sold</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $product["orders_count"] ?? 0 }} units</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Revenue</span>
                    <span class="text-sm font-semibold text-gray-900">${{ number_format(($product["orders_count"] ?? 0) * $product["price"], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Product Meta -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Meta</h2>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Created</span>
                    <span class="text-gray-900">{{ $product["created_at"]->format('M j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Last Updated</span>
                    <span class="text-gray-900">{{ $product["updated_at"]->format('M j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">SEO Score</span>
                    <span class="text-gray-900">85/100</span>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .prose {
        max-width: none;
    }
    
    .prose p {
        margin-bottom: 0;
    }
</style>
@endpush

@push('script')
<script>
    function toggleStockAlert() {
        // Implement stock alert functionality
        alert('Stock alert functionality would be implemented here');
    }


    function exportProductData() {
        // Implement export functionality
        alert('Export functionality would be implemented here');
    }

    // Image gallery functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mainImage = document.querySelector('.aspect-w-1 img');
        const thumbnails = document.querySelectorAll('.grid.grid-cols-2 img');
        
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                mainImage.src = this.src;
            });
        });
    });
</script>
@endpush