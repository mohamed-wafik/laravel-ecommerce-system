<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Same single-request shape as StoreProductRequest (fields + image files +
 * variants together), but every field is optional so partial updates work.
 * Laravel doesn't support PATCH/PUT with multipart file uploads well, so
 * the route uses POST with a `_method=PUT` spoof field — see routes/admin.php.
 */
class CreateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}