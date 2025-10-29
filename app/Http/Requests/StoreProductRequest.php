<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ];
    }
    public function messages()
    {
        return   [
            'title.required'       => 'The product title is required.',
            'title.max'            => 'The title cannot be longer than 255 characters.',
            'price.required'       => 'Please enter a price.',
            'price.numeric'        => 'The price must be a number.',
            'stock.required'       => 'Stock quantity is required.',
            'stock.integer'        => 'Stock must be an integer.',
            'discount.max'         => 'Discount cannot be more than 100%.',
            'image.image'          => 'Only images are allowed.',
            'image.mimes'          => 'Image must be a jpg, jpeg, png, or gif.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists'   => 'The selected category is invalid.',
        ];
    }
}
