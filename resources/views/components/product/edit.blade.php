@props(['product' => null,"categories" => []]) 
<button
    class="edit_item flex justify-center items-center px-3 py-3 text-base font-medium text-white bg-gray-200 rounded-lg hover:bg-gray-300 cursor-pointer"
    data-id="{{ $product['id']}}"
>
<i class="fa-solid fa-pen text-green-500"></i>
</button>
<div 
   id="edit_item_form_{{ $product['id']}}" 
    class="formEdit fixed top-0 left-0 w-full h-full z-[1000] backdrop-blur-md hidden items-center justify-center p-4"
>
    <div class="form-container bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Product</h2>
            <button  data-id="{{ $product["id"]}}" class="canecl_edit_product text-gray-500 hover:text-gray-700 text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form 
            action="{{ route("products.update" , $product["id"])}}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="space-y-6"
            data-type="product"
        >
            @csrf
            @method("PUT")
                                            
            <x-product.fields :product="$product" :categories="$categories"/>

            <div class="flex justify-end items-center gap-3">
                <button 
                    type="button" 
                    class="canecl_edit_item bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition duration-300 cursor-pointer"
                    data-id="{{ $product['id'] }}"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    data-id="{{ $product['id']}}"
                    class="submit-edit-item bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition duration-300 cursor-pointer"
                >
                submit
                </button>
            </div>
         </form>
    </div>
</div>