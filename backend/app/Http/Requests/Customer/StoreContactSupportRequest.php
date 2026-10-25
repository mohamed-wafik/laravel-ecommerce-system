<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactSupportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Open to guests and authenticated users alike
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['nullable', 'uuid', 'exists:orders,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'in:order_issue,payment_issue,product_question,complaint,other'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    /**
     * Pre-fill name/email from the authenticated user if not provided.
     */
    protected function prepareForValidation(): void
    {
        if ($user = $this->user()) {
            $this->merge([
                'name' => $this->input('name', $user->name),
                'email' => $this->input('email', $user->email),
                'phone' => $this->input('phone', $user->phone),
            ]);
        }
    }
}