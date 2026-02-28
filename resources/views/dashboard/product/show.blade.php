@extends('layout.dashboard')

@section("content")
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Products
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-900 font-semibold">Product Details</span>
            </nav>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Back
                </a>
                
                <!-- Edit Button -->
                <a 
                          href="{{ route('products.edit', $product['id']) }}" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                        Edit Product
                </a>

                <!-- Delete Button -->
                <button type="button" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl"
                        onclick="confirmItemDelete({{ $productId }})">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Delete
                </button>
                <form id="delete_item_form_{{ $productId }}" action="{{ route("products.destroy" , $productId )}}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
        
        <!-- Page Title -->
        <div class="mt-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $product["title"] }}</h1>
                    <div class="flex items-center gap-4">
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9z" clip-rule="evenodd"/>
                            </svg>
                            Product ID: <span class="font-semibold text-gray-900">#{{ $product["id"] }}</span>
                        </p>
                        <span class="text-gray-300">•</span>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Created: <span class="font-semibold text-gray-900">{{ $product["created_at"]->format('M j, Y') }}</span>
                        </p>
                    </div>
                </div>
                
                <!-- Status Badge -->
                <div>
                    @if($product->status === 'active')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-2 border-green-300 shadow-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Active
                        </span>
                    @elseif($product->status === 'draft')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border-2 border-gray-300 shadow-sm">
                            <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                            Draft
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border-2 border-red-300 shadow-sm">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Left Column - Product Images & Description -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Images -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        Product Gallery
                    </h2>
                </div>
                <div class="p-6">
                    <div class="relative group">
                        <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl overflow-hidden shadow-lg max-h-96 w-full">
                            <img 
                                id="mainImage"
                                src="{{ $product['image'] ?? asset('storage/images/default_product.webp') }}" 
                                alt="{{ $product['title'] }}" 
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($product['title']) }}&background=random'"
                            >
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-lg">
                            <span class="text-xs font-bold text-gray-700">Main Image</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        Product Description
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 leading-relaxed text-base">
                            {{ $product["description"] ?? 'No description available for this product.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Product Summary -->
        <div class="space-y-6">
            <!-- Price & Stock Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        Pricing Details
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Price -->
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Current Price</label>
                        <div class="flex items-baseline gap-3 mt-2">
                            <span class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent">
                                ${{ number_format($product["price"] - ($product["price"] * $product["discount"] / 100), 2) }}
                            </span>
                            @if($product["discount"] > 0)
                                <span class="text-xl text-gray-400 line-through">${{ number_format($product["price"], 2) }}</span>
                            @endif
                        </div>
                        @if($product["discount"] > 0)
                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 border border-green-300">
                                <svg class="w-4 h-4 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-bold text-green-800">{{ number_format($product["discount"], 2) }}% OFF</span>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <!-- Stock Status -->
                        <div class="mb-6">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-2">Stock Status</label>
                            @if($product["stock"] > 10)
                                <div class="p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-green-900">In Stock</p>
                                            <p class="text-sm text-green-700">{{ $product["stock"] }} units available</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($product["stock"] > 0)
                                <div class="p-4 rounded-xl bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-yellow-900">Low Stock</p>
                                            <p class="text-sm text-yellow-700">Only {{ $product["stock"] }} units left</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-200">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-red-900">Out of Stock</p>
                                            <p class="text-sm text-red-700">Currently unavailable</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide block mb-2">Category</label>
                            <div class="p-3 rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200">
                                <span class="inline-flex items-center gap-2 text-sm font-bold text-blue-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                    </svg>
                                    {{ $product->category["title"] ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="border-t border-gray-200 pt-6 space-y-3">
                        <button  
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-blue-700 bg-gradient-to-r from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                            </svg>
                            Set Stock Alert
                        </button>
                        
                        <button 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-purple-700 bg-gradient-to-r from-purple-50 to-purple-100 border-2 border-purple-200 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Export Product Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Analytics -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-200">
                <h2 class="text-lg font-bold text-green-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    Sales Analytics
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-3xl font-bold text-blue-700 mb-1">{{ $product["orders_count"] ?? 0 }}</div>
                        <div class="text-sm font-semibold text-blue-900">Units Sold</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl border border-green-200">
                        <div class="text-3xl font-bold text-green-700 mb-1">${{ number_format(($product["orders_count"] ?? 0) * $product["price"], 2) }}</div>
                        <div class="text-sm font-semibold text-green-900">Total Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Meta -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                <h2 class="text-lg font-bold text-purple-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Product Information
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Created Date</span>
                        <span class="text-sm font-bold text-gray-900">{{ $product["created_at"]->format('M j, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">Last Updated</span>
                        <span class="text-sm font-bold text-gray-900">{{ $product["updated_at"]->format('M j, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-semibold text-gray-600">SKU</span>
                        <span class="text-sm font-bold text-gray-900">SKU-{{ str_pad($product['id'], 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                        <span class="text-sm font-semibold text-green-700">SEO Score</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-2 bg-green-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-sm font-bold text-green-700">85/100</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@push('script')
   <script src="{{ asset('js/confirmItemDelete.js') }}" defer></script>
@endpush