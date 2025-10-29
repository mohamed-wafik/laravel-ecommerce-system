@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section("content")
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Create Product Button -->
            <x-product.create :categories="$categories" />

            <!-- Filters Section -->
            <form method="GET" id="filter-search" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Search products..." 
                        value="{{ request('search') }}" 
                        class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-600/20 shadow-sm transition-colors"
                    >
                    <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>

                <!-- Page Size Select -->
                <div class="relative">
                    <select 
                        name="pageSize" 
                        id="pageSize"
                        onchange="document.getElementById('filter-search').submit()"
                        class="appearance-none w-full sm:w-32 rounded-lg border border-gray-300 bg-white py-2 px-4 pr-8 text-gray-700 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-600/20 cursor-pointer shadow-sm transition-colors"
                    >
                        <option selected disabled>Page size</option>
                        @foreach ([10, 20, 30] as $size)
                            <option value="{{ $size }}"  {{ request("pageSize") == $size ? 'selected' : '' }} >
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </span>
                </div>

                <!-- Order By Select -->
                <div class="relative">
                    <select 
                        name="orderBy" 
                        id="orderBy"
                        onchange="document.getElementById('filter-search').submit()"
                        class="appearance-none w-full sm:w-40 rounded-lg border border-gray-300 bg-white py-2 px-4 pr-8 text-gray-700 focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-600/20 cursor-pointer shadow-sm transition-colors"
                    >
                        <option selected disabled>Order by</option>
                    @foreach (["asc", "desc"] as $order)
                        <option value="{{ $order }}"  {{ request("orderBy") == $order ? 'selected' : '' }}>
                            {{ $order }}
                        </option>
                    @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="bg-gradient-to-r from-primary-600 to-primary-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            IMAGE
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            TITLE
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            CATEGORY
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            PRICE
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            STOCK
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            ACTIONS
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if(count($products))
                        @foreach ($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                <!-- ID -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded-md">
                                        #{{ $product['id'] }}
                                    </span>
                                </td>
                                
                                <!-- IMAGE -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0">
                                        <img 
                                            class="h-12 w-12 rounded-lg object-cover border-2 border-gray-200 group-hover:border-cyan-200 transition-colors shadow-sm" 
                                            src="{{ asset('storage/' . $product['image']) }}" 
                                            alt="{{ $product['title'] }}"
                                        >
                                    </div>
                                </td>
                                
                                <!-- TITLE -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900 line-clamp-2">
                                            {{ $product['title'] }}
                                        </p>
                                    </div>
                                </td>
                                
                                <!-- CATEGORY -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-primary-800">
                                        {{ $product->category['title'] }}
                                    </span>
                                </td>
                                
                                <!-- PRICE -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">
                                        ${{ number_format($product['price'], 2) }}
                                    </span>
                                </td>
                                
                                <!-- STOCK -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $stockClass = $product['stock'] > 10 
                                            ? 'bg-green-100 text-green-800' 
                                            : ($product['stock'] > 0 
                                                ? 'bg-yellow-100 text-yellow-800' 
                                                : 'bg-red-100 text-red-800');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stockClass }}">
                                        {{ $product['stock'] }}
                                        <i class="ml-1 fa-solid fa-boxes-stacked text-xs"></i>
                                    </span>
                                </td>
                                
                                <!-- ACTIONS -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route("products.show", $product["id"])}}" class="inline-flex items-center p-2 text-gray-400 hover:text-primary-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" title="View">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <div>
                                            <x-product.edit :product="$product" :categories="$categories" />
                                        </div>
                                        
                                        <!-- Delete Button -->
                                        <div>
                                            <x-product.delete :productId="$product['id']" />
                                        </div>
                                    </div>
                                </td>
                            </tr>    
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No Products Found</p>
                                    <p class="text-sm mt-1">Get started by creating your first product</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
                
                <!-- Table Footer -->
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm font-semibold text-gray-900">
                            <div class="flex items-center">
                                <i class="fa-solid fa-cube mr-2 text-primary-600"></i>
                                Total Products
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                            <span class="bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                                {{ count($products) ?? 0 }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination (Optional) -->
        @if($products->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                </div>
                <div class="flex space-x-2">
                    @if($products->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Previous
                        </a>
                    @endif

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Next
                        </a>
                    @else
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@push('script')
    <script src="{{ asset('js/model_form_handler.js') }}"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush