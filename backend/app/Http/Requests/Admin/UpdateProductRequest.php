<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Same single-request shape as StoreProductRequest (fields + image files +
 * variants together), but every field is optional so partial updates work.
 * Laravel doesn't support PATCH/PUT with multipart file uploads well, so
 * the route uses POST with a `_method=PUT` spoof field — see routes/admin.php.
 */
class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product');

        return [
            'category_id' => ['sometimes', 'uuid', 'exists:categories,id'],
            'brand_id' => ['nullable', 'uuid', 'exists:brands,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', "unique:products,slug,{$id}"],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'sku' => ['sometimes', 'string', 'max:100', "unique:products,sku,{$id}"],
            'stock_quantity' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],

            // Sending `images` at all replaces the existing gallery (old
            // Cloudinary assets are cleaned up by ProductService). Omit the
            // field entirely to leave the current images untouched.
            'images' => ['sometimes', 'array', 'max:8'],
            'images.*' => [
                'file', 'image', 'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico',
            ],

            // Sending `variants` replaces the variant set (existing ones
            // matched by id are updated, rest are recreated, missing ones removed).
            'variants' => ['sometimes', 'array'],
            'variants.*.id' => ['nullable', 'uuid', 'exists:product_variants,id'],
            'variants.*.color' => ['nullable', 'string', 'max:100'],
            'variants.*.size' => ['nullable', 'string', 'max:50'],
            'variants.*.sku' => ['required_with:variants', 'string', 'max:100'],
            'variants.*.price_override' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
