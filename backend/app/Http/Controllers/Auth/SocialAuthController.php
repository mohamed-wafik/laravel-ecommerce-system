<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

/**
 * Two ways in, same result — both end up calling
 * AuthService::loginWithGoogle() and returning { access_token,
 * refresh_token, user }:
 *
 *  - loginWithToken(): the API-first path. A web/mobile client uses
 *    Google's own Sign-In SDK client-side, gets an ID token, and posts it
 *    here. No redirect through this backend at all — the right fit for an
 *    SPA/mobile app talking to this API.
 *  - redirect()/callback(): the classic OAuth redirect path, for a
 *    server-rendered flow (or a web client happy to be redirected through
 *    this backend instead of using Google's JS SDK directly).
 */
class SocialAuthController extends Controller
{
    public function __construct(
        protected AuthService $auth,
    ) {}

    protected function googleProvider()
    {
        $provider = Socialite::driver('google');

        if (method_exists($provider, 'stateless')) {
            return $provider->stateless();
        }

        return $provider;
    }

    public function loginWithToken(GoogleLoginRequest $request)
    {
        try {
            $googleUser = $this->googleProvider()->userFromToken($request->id_token);
        } catch (Throwable $e) {
            return $this->error('Invalid Google token.', 401);
        }

        return $this->respondWithGoogleUser($request, $googleUser->getId(), $googleUser->getEmail(), $googleUser->getName(), $googleUser->getAvatar());
    }

    public function redirect()
    {
        return $this->googleProvider()->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = $this->googleProvider()->user();
        } catch (Throwable $e) {
            return $this->error('Google authentication failed.', 401);
        }

        return $this->respondWithGoogleUser($request, $googleUser->getId(), $googleUser->getEmail(), $googleUser->getName(), $googleUser->getAvatar());
    }

    protected function respondWithGoogleUser(Request $request, string $googleId, string $email, string $name, ?string $avatar)
    {
        try {
            $tokens = $this->auth->loginWithGoogle($googleId, $email, $name, $avatar);
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->success($tokens, 'Logged in with Google');
    }
}