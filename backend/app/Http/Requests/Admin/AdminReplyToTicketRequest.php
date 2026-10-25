<?php

namespace App\Http\Requests\Admin;

use App\Models\SupportTicket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminReplyToTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:5000'],
            'status' => ['nullable', Rule::in(SupportTicket::STATUSES)],
        ];
    }
}