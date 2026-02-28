@extends('layout.dashboard')

@section("content")
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Page Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-gray-600 mt-1">Manage your store products</p>
            </div>
            
            <!-- Back Button -->
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('products.index') }}" 
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition duration-200 border border-gray-300"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Product Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">Edit Product</h2>
                        <p class="text-blue-100 text-sm">Update product information</p>
                    </div>
                </div>
                <span class="bg-white/20 text-white text-xs font-medium px-3 py-1 rounded-full">
                    ID: {{ $product['id'] }}
                </span>
            </div>
        </div>

        <!-- Form Body -->
        <div class="p-6">
            <form 
                action="{{ route('products.update', $product['id']) }}" 
                method="POST" 
                enctype="multipart/form-data"
                class="space-y-6"
                data-type="product"
                id="productForm"
            >
                @csrf
                @method("PUT")
                
                <x-product.fields :product="$product" :categories="$categories" />

                <!-- Form Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('products.index') }}"
                        class="cancel-product inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200 border border-gray-300"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        data-id="{{ $product['id'] }}"
                        class="submit-product inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('js/formValidationProduct.js') }}" defer></script>
@endpush