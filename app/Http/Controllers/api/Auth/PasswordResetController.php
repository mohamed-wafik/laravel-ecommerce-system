<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\api\BaseController;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends BaseController
{
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower(trim($request->email));

        $rateLimitKey = 'send-otp:' . $email;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return $this->sendError([
                'message' => "Too many attempts. Please wait {$seconds} seconds.",
            ],429);
        }
        RateLimiter::hit($rateLimitKey, 15 * 60);

        $user = User::where('email', $email)->first();

        if ($user) {
            PasswordResetOtp::where('email', $email)->delete();

            $otpPlain = (string) random_int(100000, 999999); 

            PasswordResetOtp::create([
                'email'      => $email,
                'otp'        => Hash::make($otpPlain),
                'token'      => '',              
                'attempts'   => 0,
                'verified'   => false,
                'expires_at' => now()->addMinutes(10),
            ]);

            // Send OTP notification
            // $user->notify(new OtpNotification($otpPlain));
        }

        return $this->sendResponse(null, 'If this email is registered, you will receive an OTP shortly.');

    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        $email = strtolower(trim($request->email));


        $ipKey = 'verify-otp:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return $this->sendError([
                'message' => "Too many attempts. Please slow down.",
            ],429);
        }
        RateLimiter::hit($ipKey, 60);

        $record = PasswordResetOtp::where('email', $email)
            ->where('verified', false)
            ->latest()
            ->first();

        $fail = fn () =>  $this->sendError(['message' => 'Invalid or expired OTP.'], 422);

        if (!$record) return $fail();
        if ($record->isExpired()) {
            $record->delete();
            return $fail();
        }
        if ($record->isMaxAttemptsReached()) {
            $record->delete();
            return $this->sendError(['message' => 'Too many failed attempts. Please request a new OTP.'], 422);
        }

        if (!$record->verifyOtp($request->otp)) {
            $record->increment('attempts');
            return $fail();
        }

        // OTP is correct — generate a secure reset token
        $token = Str::random(64);

        $record->update([
            'token'      => Hash::make($token),
            'verified'   => true,
            'expires_at' => now()->addMinutes(15), // token valid 15 min
        ]);

        RateLimiter::clear($ipKey);

        return $this->sendResponse(['token'   => $token],"OTP verified successfully.");

    }
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'token'    => ['required', 'string', 'size:64'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        $email = strtolower(trim($request->email));

        $resetKey = 'reset-password:' . $email;

        if (RateLimiter::tooManyAttempts($resetKey, 5)) {
            return $this->sendError(['message' => 'Too many reset attempts. Please try again later.'], 429);
        }

        RateLimiter::hit($resetKey, 3600);

        $record = PasswordResetOtp::where('email', $email)
            ->where('verified', true)
            ->latest()
            ->first();

        if (! $record || $record->isExpired() || ! Hash::check($request->token, $record->token)) {
            return $this->sendError(['message' => 'Invalid or expired reset token.'], 422);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return $this->sendError(['message' => 'User not found.'], 404);
        }

        if (Hash::check($request->password, $user->password)) {

            return $this->sendError(['message' => 'New password must be different from your current password.'], 422);
        }

        $user->forceFill(['password' => Hash::make($request->password)])->save();
        $user->tokens()->delete();    

        $record->delete();
        RateLimiter::clear($resetKey);

        return $this->sendResponse(null,'Password reset successfully. Please log in.');

    }
}