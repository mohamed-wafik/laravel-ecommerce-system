@props(['product' => null, 'categories' => []]) 

<!-- Title Input -->
<div class="form-group">
    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
        Product Title <span class="text-red-500">*</span>
    </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <input 
            type="text" 
            name="title" 
            value="{{ old('title', $product['title'] ?? '') }}"
            id="title" 
            placeholder="Enter product title"
            class="mt-1 w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
        >
    </div>
    <p class="text-xs text-gray-500 mt-1">Minimum 3 characters, maximum 200 characters</p>
    @error('title')
        <div class="error-message flex items-center gap-2 text-red-600 text-sm mt-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>

<!-- Description Input -->
<div class="form-group">
    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
        Description <span class="text-red-500">*</span>
    </label>
    <div class="relative">
        <textarea 
            name="description" 
            id="description" 
            rows="4"
            placeholder="Enter product description"
            class="mt-1 w-full rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-3 transition duration-200 resize-none"
        >{{ old('description', $product['description'] ?? '') }}</textarea>
        <div class="absolute bottom-2 right-2 text-xs text-gray-400">
            <span id="product_charCount">0</span>/1000
        </div>
    </div>
    <p class="text-xs text-gray-500 mt-1">Minimum 10 characters, maximum 1000 characters</p>
    @error('description')
        <div class="error-message flex items-center gap-2 text-red-600 text-sm mt-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>

<!-- Price and Stock Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Price Input -->
    <div class="form-group">
        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
            Price <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 text-sm font-medium">$</span>
            </div>
            <input 
                type="number" 
                step="0.01" 
                min="0"
                value="{{ old('price', $product['price'] ?? '') }}"
                name="price" 
                id="price" 
                placeholder="0.00"
                class="mt-1 w-full pl-8 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
            >
        </div>
        <p class="text-xs text-gray-500 mt-1">Must be greater than 0</p>
        @error('price')
            <div class="error-message flex items-center gap-2 text-red-600 text-sm mt-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>

    <!-- Stock Input -->
    <div class="form-group">
        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
            Stock Quantity <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <input 
                type="number" 
                name="stock"
                min="0"
                value="{{ old('stock', $product['stock'] ?? '') }}" 
                id="stock" 
                placeholder="0"
                class="mt-1 w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
            >
        </div>
        <p class="text-xs text-gray-500 mt-1">Available quantity (minimum 0)</p>
        @error('stock')
            <div class="error-message flex items-center gap-2 text-red-600 text-sm mt-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>
</div>

<!-- Discount and Category Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Discount Input -->
    <div class="form-group">
        <label for="discount" class="block text-sm font-semibold text-gray-700 mb-2">
            Discount (%)
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <input 
                type="number" 
                step="0.01" 
                min="0"
                max="100"
                name="discount" 
                value="{{ old('discount', $product['discount'] ?? '') }}"
                id="discount" 
                placeholder="0.00"
                class="mt-1 w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
            >
        </div>
        <p class="text-xs text-gray-500 mt-1">Optional - Between 0 and 100</p>
        @error('discount')
            <div class="error-message flex items-center gap-2 text-red-600 text-sm mt-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>

    <!-- Category Select -->
    <div class="form-group">
        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
            Category <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <select 
                name="category_id" 
                id="category_id"
                class="mt-1 w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white transition duration-200 appearance-none"
            >
                <option value="">Select a category</option>
                @foreach($categories as $category) 
                    <option 
                        value="{{ $category['id'] }}" 
                        {{ old('category_id', $product['category_id'] ?? '') == $category['id'] ? 'selected' : '' }}
                    >
                        {{ $category['title'] }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-1">Select product category</p>
        @error('category_id')
            <div class="error-message flex items-center gap-2 text-red-600 text-sm mt-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>
</div>

<!-- Image Upload -->
<div class="form-group">
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Product Image <span class="text-red-500">*</span>
    </label>
    <div 
        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer"
        id="product_dropArea"
    >
        <svg class="mx-auto h-16 w-16 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <div class="mt-4 flex justify-center text-sm text-gray-600">
            <label for="product_image" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-2">
                <span>Upload a file</span>
                <input 
                    type="file" 
                    name="image" 
                    id="product_image"
                    accept="image/*"
                    class="sr-only"
                >
            </label>
            <p class="pl-1">or drag and drop</p>
        </div>
        <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF, WEBP up to 10MB</p>
    </div>
    
    <!-- Current Image Preview (if exists) -->
    @if(isset($product['image']) && $product['image'])
    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <p class="text-sm font-medium text-gray-700 mb-2">Current Image:</p>
        <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['title'] ?? 'Product' }}" class="max-h-32 rounded-lg mx-auto">
    </div>
    @endif
    
    @error('image')
        <div class="error-message flex items-center gap-2 text-red-600 text-sm mt-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>