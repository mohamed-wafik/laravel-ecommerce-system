@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section("content")
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Page Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-600 mt-1">Manage your product categories</p>
            </div>
            
            <!-- Create Category Button -->
            <div class="flex items-center gap-3">
                <x-category.create />
            </div>
        </div>
    </div>

    <!-- Categories Table -->
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
                            DESCRIPTION
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            PRODUCTS
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">
                            ACTIONS
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if(count($categories))
                        @foreach ($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                <!-- ID -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-md">
                                        #{{ $category['id'] }}
                                    </span>
                                </td>
                                
                                <!-- IMAGE -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0">
                                        <img 
                                            class="h-12 w-12 rounded-lg object-cover border-2 border-gray-200 group-hover:border-cyan-200 transition-colors shadow-sm" 
                                            src="{{  $category['image'] }}" 
                                            alt="{{ $category['title'] }}"
                                            onerror="this.src='https://via.placeholder.com/48?text=📁'"
                                        >
                                    </div>
                                </td>
                                
                                <!-- TITLE -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $category['title'] }}
                                        </p>
                                    </div>
                                </td>
                                
                                <!-- DESCRIPTION -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 line-clamp-2 max-w-xs">
                                        {{ $category['description'] ?: 'No description' }}
                                    </p>
                                </td>
                                
                                <!-- PRODUCTS COUNT -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $productsCount = count($category['products']);
                                        $countClass = $productsCount > 0 
                                            ? 'bg-blue-100 text-blue-800 border-blue-200' 
                                            : 'bg-gray-100 text-gray-600 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $countClass }}">
                                        <i class="fa-solid fa-cube mr-2 text-xs"></i>
                                        {{ $productsCount }}
                                    </span>
                                </td>
                                
                                <!-- ACTIONS -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route("categories.show" , $category["id"])}}" 
                                           class="inline-flex items-center p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" 
                                           title="View Category">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <div>
                                            <x-category.edit :category="$category" />
                                        </div>
                                        
                                        <!-- Delete Button -->
                                        <div>
                                            <x-category.delete :category="$category" />
                                        </div>
                                    </div>
                                </td>
                            </tr>    
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-folder-open text-5xl mb-4"></i>
                                    <p class="text-xl font-medium mb-2">No Categories Found</p>
                                    <p class="text-sm">Create your first category to organize products</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
                
                <!-- Table Footer -->
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm font-semibold text-gray-900">
                            <div class="flex items-center">
                                <i class="fa-solid fa-layer-group mr-2 text-cyan-600"></i>
                                Total Categories
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                            <span class="bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                                {{ count($categories) ?? 0 }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination (Optional) -->
        @if(isset($categories) && method_exists($categories, 'hasPages') && $categories->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories
                </div>
                <div class="flex space-x-2">
                    @if($categories->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed text-sm">
                            <i class="fa-solid fa-chevron-left mr-2"></i>Previous
                        </span>
                    @else
                        <a href="{{ $categories->previousPageUrl() }}" 
                           class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            <i class="fa-solid fa-chevron-left mr-2"></i>Previous
                        </a>
                    @endif

                    @if($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}" 
                           class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            Next<i class="fa-solid fa-chevron-right ml-2"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed text-sm">
                            Next<i class="fa-solid fa-chevron-right ml-2"></i>
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