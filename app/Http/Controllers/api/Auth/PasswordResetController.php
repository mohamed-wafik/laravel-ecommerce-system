<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\api\BaseController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends BaseController
{
    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status !== Password::RESET_LINK_SENT) {
                throw ValidationException::withMessages([
                    'email' => [__($status)],
                ]);
            }

            return $this->sendResponse(
                ['status' => __($status)],
                'Password reset link sent successfully'
            );

        } catch (ValidationException $e) {
            return $this->sendError('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('An error occurred while sending the reset link', [], 500);
        }
    }

    /**
     * Verify reset token is valid
     */
    public function verifyToken(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'token' => ['required', 'string'],
            ]);

            $status = Password::tokenExists(
                $request->get('email'),
                $request->get('token')
            );

            if (!$status) {
                throw ValidationException::withMessages([
                    'token' => ['The provided token is invalid or has expired.'],
                ]);
            }

            return $this->sendResponse(
                ['status' => 'Token is valid'],
                'Token verified successfully'
            );

        } catch (ValidationException $e) {
            return $this->sendError('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('An error occurred while verifying the token', [], 500);
        }
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', PasswordRule::defaults()],
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status !== Password::PASSWORD_RESET) {
                throw ValidationException::withMessages([
                    'email' => [__($status)],
                ]);
            }

            return $this->sendResponse(
                ['status' => __($status)],
                'Password has been reset successfully'
            );

        } catch (ValidationException $e) {
            return $this->sendError('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('An error occurred while resetting the password', [], 500);
        }
    }
}