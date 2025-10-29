@props(["categories" => []]) 
<button 
    id="add_new_item"
    class="inline-flex justify-center items-center bg-primary-600 text-white text-base py-2.5 px-4 rounded-lg transition-all duration-300 hover:bg-primary-500 cursor-pointer"
>
    + Create New Product
</button>
<div 
    id="add_new_item_form" 
    class="fixed top-0 left-0 w-full h-full z-[1000] backdrop-blur-md hidden items-center justify-center p-4"
>
    <div class="form-container bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Add New Product</h2>
            <button 
                id="closeForm" 
                class="text-gray-500 hover:text-gray-700 text-xl"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form 
            action="" 
            method="POST" 
            enctype="multipart/form-data" 
            class="space-y-6" 
            data-type="product"
        >
            @csrf

            <x-product.fields :categories="$categories" />

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
                    class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition duration-300 cursor-pointer"
                >
                    submit
                </button>
            </div>
        </form>
    </div>
</div>