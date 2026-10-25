<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * multipart/form-data body:
 *   name    Nike
 *   logo    (file) nike-logo.png
 *   is_active 1
 */
class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:brands,slug'],
            'logo' => [
                'nullable', 'file', 'image', 'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico',
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
