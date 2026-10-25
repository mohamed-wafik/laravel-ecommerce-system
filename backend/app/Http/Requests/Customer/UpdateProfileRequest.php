<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', "unique:users,phone,{$userId}"],
            'avatar' => [
                'nullable', 'file', 'image',
                'mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico',
            ],
        ];
    }
}