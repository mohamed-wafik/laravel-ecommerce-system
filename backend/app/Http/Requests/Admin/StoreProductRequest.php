<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Everything needed to create a product in ONE request:
 *   - product fields
 *   - images as real uploaded files (multipart), NOT pre-uploaded via
 *     a separate /admin/media call — ProductService uploads them to
 *     Cloudinary itself while creating the product
 *   - variants as a plain nested array, created alongside the product
 *
 * Example multipart/form-data body (e.g. from Postman or FormData in JS):
 *
 *   name                        Red Running Shoes
 *   category_id                 9c1f5e2a-....
 *   brand_id                    3a2b7c10-....
 *   description                 Lightweight everyday trainer
 *   price                       59.99
 *   compare_price               79.99
 *   sku                         SHOE-RED-001
 *   stock_quantity              40
 *   is_active                   1
 *   images[]                    (file) red-1.png
 *   images[]                    (file) red-2.webp
 *   variants[0][color]          Red
 *   variants[0][size]           42
 *   variants[0][sku]            SHOE-RED-001-42
 *   variants[0][stock_quantity] 10
 *   variants[1][color]          Red
 *   variants[1][size]           43
 *   variants[1][sku]            SHOE-RED-001-43
 *   variants[1][stock_quantity] 15
 */
class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gate this at the route/middleware level (auth:sanctum + can:admin)
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'brand_id' => ['nullable', 'uuid', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            // Real files — uploaded to Cloudinary inside ProductService, not beforehand.
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => [
                'file', 'image', 'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico',
            ],

            // Variants created together with the product in the same transaction.
            'variants' => ['nullable', 'array'],
            'variants.*.color' => ['nullable', 'string', 'max:100'],
            'variants.*.size' => ['nullable', 'string', 'max:50'],
            'variants.*.sku' => ['required_with:variants', 'string', 'max:100', 'distinct'],
            'variants.*.price_override' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.image' => 'Each file in images must be a valid image.',
            'variants.*.sku.distinct' => 'Variant SKUs must be unique within the product.',
        ];
    }
}
