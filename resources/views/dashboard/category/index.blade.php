@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section("content")
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Categories</h1>
                <p class="text-gray-600 mt-2">Organize and manage your product categories</p>
            </div>
            
            <!-- Create Category Button -->
            <a href="{{ route('categories.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl hover:from-green-700 hover:to-green-800 shadow-lg shadow-green-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-green-500/40 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Add New Category
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-800 to-gray-900">
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9z" clip-rule="evenodd"/>
                                </svg>
                                ID
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                                Image
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                </svg>
                                Title
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                Description
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                </svg>
                                Products
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                                Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if(count($categories))
                        @foreach ($categories as $category)
                            <tr class="hover:bg-green-50/50 transition-all duration-200 group">
                                <!-- ID -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-900 border border-gray-300 shadow-sm">
                                        #{{ $category['id'] }}
                                    </span>
                                </td>
                                
                                <!-- IMAGE -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="relative">
                                        <img 
                                            class="h-14 w-14 rounded-xl object-cover border-2 border-gray-200 group-hover:border-green-400 group-hover:shadow-lg transition-all shadow-sm" 
                                            src="{{ $category['image'] ?? asset('images/default-category.png') }}" 
                                            alt="{{ $category['title'] }}"
                                           onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($category['title']) }}&background=random'"
                                        >
                                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                                    </div>
                                </td>
                                
                                <!-- TITLE -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                                            <svg class="w-4 h-4 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-green-600 transition-colors">
                                            {{ $category['title'] }}
                                        </p>
                                    </div>
                                </td>
                                
                                <!-- DESCRIPTION -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 line-clamp-2 max-w-xs">
                                        {{ $category['description'] ?: 'No description available' }}
                                    </p>
                                </td>
                                
                                <!-- PRODUCTS COUNT -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $productsCount = count($category['products']);
                                    @endphp
                                    @if($productsCount > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-900 border border-blue-300 shadow-sm">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $productsCount }} {{ $productsCount === 1 ? 'Product' : 'Products' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 border border-gray-300 shadow-sm">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                            </svg>
                                            No Products
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- ACTIONS -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('categories.show', $category['id'])}}" 
                                           class="inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-lg transition-all duration-200 hover:shadow-md group/btn" 
                                           title="View Details">
                                            <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('categories.edit', $category['id'])}}" 
                                           class="inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-100 hover:bg-green-200 rounded-lg transition-all duration-200 hover:shadow-md group/btn" 
                                           title="Edit Category">
                                            <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </a>
                                        
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
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 mb-2">No Categories Found</p>
                                    <p class="text-sm text-gray-500 mb-6">Create your first category to organize products</p>
                                    <a href="{{ route('categories.create') }}" 
                                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl hover:from-green-700 hover:to-green-800 shadow-lg transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Create Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
                
                <!-- Table Footer -->
                <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4">
                            <div class="flex items-center gap-2 text-sm font-bold text-gray-900">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                    </svg>
                                </div>
                                Total Categories
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-white border-2 border-gray-300 text-gray-900 shadow-sm">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                </svg>
                                {{ count($categories) ?? 0 }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($categories) && method_exists($categories, 'hasPages') && $categories->hasPages())
            @include('components.Pagination', [
                'firstItem' => $categories->firstItem(),
                'lastItem' => $categories->lastItem(),
                'total' => $categories->total(),
                'previousPageUrl' => $categories->previousPageUrl(),
                'nextPageUrl' => $categories->nextPageUrl(),
                'onFirstPage' => $categories->onFirstPage(),
                'hasMorePages' => $categories->hasMorePages()
            ])
        @endif
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/confirmItemDelete.js') }}" defer></script>
@endpush