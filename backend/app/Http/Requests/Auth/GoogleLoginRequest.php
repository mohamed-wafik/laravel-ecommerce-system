<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * For SPA/mobile clients using Google's native Sign-In SDK (Google
 * Identity Services on web, GoogleSignIn on iOS/Android) — the client
 * gets an ID token directly from Google and hands it to the backend,
 * no redirect needed. See SocialAuthController::loginWithToken().
 */
class GoogleLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_token' => ['required', 'string'],
        ];
    }
}
