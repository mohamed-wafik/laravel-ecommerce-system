<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Sent as POST + `_method=PUT` (method spoof) so the multipart `logo` file
 * uploads correctly — PHP doesn't parse multipart bodies on native PUT.
 */
class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('brand');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', "unique:brands,slug,{$id}"],
            'logo' => [
                'nullable', 'file', 'image', 'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico',
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
