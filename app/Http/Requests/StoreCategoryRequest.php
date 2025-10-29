<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'title' => "required|string|max:255|unique:categories,title",
            'description'  => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The category title is required.',
            'title.string'   => 'The category title must be a valid string.',
            'title.max'      => 'The category title may not be greater than 255 characters.',

            'description.string'    => 'The category body must be a valid string.',
            'description.max'       => 'The category body may not be greater than 1000 characters.',
        ];
    }
}
