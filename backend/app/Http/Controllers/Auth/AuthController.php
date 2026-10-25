<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckOtpPassword;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $auth,
    ) {}

    /**
     * 1) register -> account created, OTP sent, no tokens yet.
     */
    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->auth->register($request->validated());
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->created($result, 'Registered');
    }

    /**
     * 2) verify OTP -> issues { access_token, refresh_token }.
     * If the request carries a guest cart token, it's merged into the
     * user's cart now that they have an account (see GuestCartService::mergeIntoUser).
     */
    public function verifyRegister(VerifyOtpRequest $request)
    {
        try {
            $tokens = $this->auth->verifyRegistration($request->email, $request->code);
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->success($tokens, 'Verified');
    }

    /**
     * login -> if email already verified: issues tokens directly.
     *          if NOT verified: sends an OTP and returns verified:false
     *          instead — client then calls verifyLogin().
     */
    public function login(LoginRequest $request)
    {
        try {
            $result = $this->auth->login($request->email, $request->password);
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        if ($result['verified'] === false) {
            return $this->success($result, 'Verification code sent');
        }

        return $this->success($result, 'Logged in');
    }

    public function verifyLogin(VerifyOtpRequest $request)
    {
        try {
            $tokens = $this->auth->verifyLogin($request->email, $request->code);
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->success($tokens, 'Logged in');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->auth->forgotPassword($request->email);

        // Same response whether or not the email exists — don't leak account existence.
        return $this->success(null, 'If that email is registered, a reset code has been sent.');
    }
    public function checkOtpPassword(CheckOtpPassword $request)
    {
        return $this->success([
            'reset_token' => $this->auth->checkOtp(
                $request->email,
                $request->code
            ),
        ]);
    }

    public function resetPassword(
        ResetPasswordRequest $request
    ) {
        try {
            $tokens = $this->auth->resetPassword(
                $request->user(),
                $request->password
            );
        } catch (ValidationException $e) {
            return $this->validationError(
                $e->errors(),
                $e->getMessage()
            );
        }

        return $this->success(
            $tokens,
            'Password reset successfully.'
        );
    }

    /**
     * Exchanges a refresh_token for a new { access_token, refresh_token }
     * pair. Requires `auth:sanctum` (see routes/auth.php) — this only
     * checks the authenticated token actually carries the
     * 'issue-access-token' ability, i.e. it IS a refresh token and not an
     * access token trying to mint another one.
     */
    public function refresh(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if (! $token || ! $token->can('issue-access-token')) {
            return $this->forbidden('This token cannot be used to refresh.');
        }

        return $this->success($this->auth->refresh($token), 'Token refreshed');
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->user(), $request->user()->currentAccessToken());

        return $this->noContentSuccess('Logged out');
    }

    public function logoutAll(Request $request)
    {
        $this->auth->logoutAllDevices($request->user());

        return $this->noContentSuccess('Logged out of all devices');
    }

    public function me(Request $request)
    {
        return $this->success($request->user());
    }
}