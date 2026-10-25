<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Customer-side reply — just a message, no status control (only admins
 * can change a ticket's status; see Admin\AdminReplyToTicketRequest for
 * the admin equivalent, which additionally accepts an optional `status`).
 */
class ReplyToTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
