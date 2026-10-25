<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactSupportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'in:open,in_progress,resolved,closed'],
            'admin_response' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'handled_by' => ['sometimes', 'nullable', 'uuid', 'exists:users,id'],
        ];
    }
}