@props(['product' => null,"categories" => []]) 
<div>
    <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
    <input 
        type="text" 
        name="title" 
        value="{{ $product["title"] ?? '' }}"
        id="title" 
        placeholder="Enter product title"
        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-blue-500 focus:ring focus:ring-blue-200 px-3 py-2"
    >
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
    <textarea 
        name="description" 
        id="description" 
        placeholder="Enter product description"
        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 h-28"
    >{{ $product["description"] ?? '' }}</textarea>
</div>

<div class="grid grid-cols-2 gap-6">
    <div>
        <label for="price" class="block text-sm font-medium text-gray-600">Price</label>
        <input 
            type="number" 
            step="0.01" 
            value="{{ $product["price"] ?? '' }}"
            name="price" id="price" 
            placeholder="0.00"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-blue-500 focus:ring focus:ring-blue-200 px-3 py-2"
        >
    </div>
    <div>
        <label for="stock" class="block text-sm font-medium text-gray-600">Stock</label>
        <input 
            type="number" 
            name="stock"
            value="{{ $product["stock"] ?? '' }}" 
            id="stock" 
            placeholder="0"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-blue-500 focus:ring focus:ring-blue-200 px-3 py-2"
        >
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    <div>
        <label for="discount" class="block text-sm font-medium text-gray-600">Discount (%)</label>
        <input 
            type="number" 
            step="0.01" 
            name="discount" 
            value="{{ $product["discount"] ?? '' }}"
            id="discount" 
            placeholder="Enter discount"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:ring focus:shadow-blue-500 focus:ring-blue-200 px-3 py-2"
        >
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-600">Category</label>
        <select 
            name="category_id" 
            id="category_id"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 bg-white"
        >
            @forEach($categories as $category) 
                 <option value="{{ $category['id']}}" {{ isset($product->category) && $product->category['id'] == $category['id'] ? 'selected' : '' }}>{{ $category["title"] }}</option>
            @endforeach>
        </select>
    </div>
</div>

<div 
    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center transition-colors duration-200"
    id="dropArea"
>
    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    <div class="mt-4 flex justify-center text-sm text-gray-600">
        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
            <span class="text-center">Upload a file</span>
            <input 
                type="file" 
                name="image" 
                id="image"
                accept="image/*"
            >
        </label>
        <p class="pl-1">or drag and drop</p>
    </div>
    <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 10MB</p>
</div>
