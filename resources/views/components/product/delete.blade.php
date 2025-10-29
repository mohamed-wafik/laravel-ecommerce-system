@props(["productId" => null]) 
<button 
    class="delete_item py-2 px-3 text-base font-medium text-white bg-gray-200 rounded-lg hover:bg-gray-300 cursor-pointer"
    onclick="confirmItemDelete({{ $productId }})"
>
    <i class="fa-solid fa-trash text-red-500"></i>
</button>
<form id="delete_item_form_{{ $productId }}" action="{{ route("products.destroy" , $productId )}}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

