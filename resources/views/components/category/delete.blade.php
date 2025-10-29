@props(["category" => []]) 
<button 
    class="delete_category py-2 px-3 text-base font-medium text-white bg-gray-200 rounded-lg hover:bg-gray-300 cursor-pointer"
    onclick="confirmItemDelete({{$category['id']}})"
>
    <i class="fa-solid fa-trash text-red-500"></i>
</button>
<form id="delete_item_form_{{ $category['id'] }}" action="{{ route("categories.destroy" , $category['id'] )}}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>