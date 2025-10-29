<button 
    id="add_new_item"
    class="inline-flex justify-center items-center bg-primary-600 text-white text-base py-2.5 px-4 rounded-lg transition-all duration-300 hover:bg-primary-500 cursor-pointer"
>
    + Add category
</button>
<div 
    id="add_new_item_form" 
    class="fixed top-0 left-0 w-full h-full z-[1000] backdrop-blur-md hidden items-center justify-center p-4"
>
    <div class="form-container bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Add New Category</h2>
            <button id="closeForm" class="text-gray-500 hover:text-gray-700 text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form 
            action="{{ route("categories.store")}}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="space-y-6"
            data-type="category"
        >
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-600">Title</label>
                <input 
                type="text" 
                name="title" 
                id="title" 
                placeholder="Enter category title"
                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-blue-500 focus:ring focus:ring-blue-200 px-3 py-2"
                >
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        placeholder="Enter category description"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-blue-500 focus:ring focus:ring-blue-200 px-3 py-2 h-28"
                    ></textarea>
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
                            class="sr-only"
                        >
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 10MB</p>
            </div>

            <div class="flex justify-end items-center gap-3">
                <button 
                id="canecl_add_item"
                type="button" 
                class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition duration-300 cursor-pointer"
                >
                    Cancel
                </button>
                <button 
                type="submit" 

                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition duration-300 cursor-pointer"
                >
                    submit
                </button>
            </div>
        </form>
    </div>
</div>