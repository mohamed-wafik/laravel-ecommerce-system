@props(['category' => null]) 

<!-- Title Input -->
<div class="form-group">
    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
        Title <span class="text-red-500">*</span>
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
            value="{{ $category['title'] ?? '' }}"
            id="title" 
            placeholder="Enter category title"
            class="mt-1 w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
        >
    </div>
    <p class="text-xs text-gray-500 mt-1">Minimum 3 characters, maximum 100 characters</p>
</div>

<!-- Description Input -->
<div class="form-group">
    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
        Description
    </label>
    <div class="relative">
        <textarea 
            name="description" 
            id="description" 
            rows="4"
            placeholder="Enter category description (optional)"
            class="mt-1 w-full rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 px-4 py-3 transition duration-200 resize-none"
        >{{ $category['description'] ?? '' }}</textarea>
        <div class="absolute bottom-2 right-2 text-xs text-gray-400">
            <span id="charCount">0</span>/500
        </div>
    </div>
    <p class="text-xs text-gray-500 mt-1">Optional - Maximum 500 characters</p>
</div>

<!-- Image Upload -->
<div class="form-group">
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Category Image
    </label>
    <div 
        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer"
        id="dropArea"
    >
        <svg class="mx-auto h-16 w-16 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <div class="mt-4 flex justify-center text-sm text-gray-600">
            <label for="image" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-2">
                <span>Upload a file</span>
                <input 
                    type="file" 
                    name="image" 
                    id="image"
                    accept="image/*"
                    class="sr-only"
                >
            </label>
            <p class="pl-1">or drag and drop</p>
        </div>
        <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF, WEBP up to 10MB</p>
    </div>
    
    <!-- Current Image Preview (if exists) -->
    @if(isset($category['image']) && $category['image'])
    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <p class="text-sm font-medium text-gray-700 mb-2">Current Image:</p>
        <img src="{{ asset('storage/' . $category['image']) }}" alt="{{ $category['title'] }}" class="max-h-32 rounded-lg mx-auto">
    </div>
    @endif
</div>