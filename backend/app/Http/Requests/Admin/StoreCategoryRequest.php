<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * multipart/form-data body:
 *   parent_id  (optional)
 *   name       Men's Shoes
 *   image      (file) mens-shoes.png
 *   is_active  1
 *   sort_order 0
 */
class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'uuid', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:categories,slug'],
            'image' => [
                'nullable', 'file', 'image', 'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico',
            ],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
