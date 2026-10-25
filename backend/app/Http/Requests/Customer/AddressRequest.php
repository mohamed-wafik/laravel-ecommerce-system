<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared by both create and update — every field is validated the same
 * way either time, `sometimes` just lets update() send a partial payload.
 */
class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'label' => ['nullable', 'string', 'max:50'],
            'full_name' => [$method, 'string', 'max:255'],
            'phone' => [$method, 'string', 'max:30'],
            'country' => [$method, 'string', 'max:100'],
            'city' => [$method, 'string', 'max:100'],
            'street' => [$method, 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }
}
