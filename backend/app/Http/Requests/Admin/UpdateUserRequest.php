<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Sent as POST + `_method=PUT` so the multipart `avatar` file uploads correctly.
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', "unique:users,email,{$id}"],
            'phone' => ['sometimes', 'nullable', 'string', "unique:users,phone,{$id}"],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'role' => ['sometimes', 'in:customer,admin'],
            'is_active' => ['sometimes', 'boolean'],
            'avatar' => [
                'nullable', 'file', 'image', 'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico',
            ],
        ];
    }
}
