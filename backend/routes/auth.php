<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

// Register in routes/api.php:
//   Route::prefix('auth')->group(base_path('routes/auth.php'));
//
// Flow:
//   1. POST /auth/register          -> account created, OTP sent
//   2. POST /auth/register/verify   -> { access_token, refresh_token }
//   ---
//   3. POST /auth/login             -> tokens if already verified,
//                                       otherwise sends an OTP and
//                                       returns { verified: false }
//   4. POST /auth/login/verify      -> { access_token, refresh_token }
//   ---
//   5. POST /auth/forgot-password   -> sends OTP if the email exists
//   6. POST /auth/reset-password    -> verifies OTP, sets new password
//   ---
//   7. POST /auth/refresh  (auth:sanctum, refresh_token)  -> new token pair
//   8. POST /auth/logout   (auth:sanctum, access_token)   -> revoke current token
//   9. POST /auth/logout-all (auth:sanctum)               -> revoke all tokens
//  10. GET  /auth/me       (auth:sanctum)                 -> current user
//  ---
//  11. POST /auth/google            { id_token }  -> tokens (API-first, SPA/mobile)
//  12. GET  /auth/google/redirect   -> redirects to Google (classic OAuth flow)
//  13. GET  /auth/google/callback   -> Google redirects back here -> tokens

Route::post('register', [AuthController::class, 'register'])->middleware('throttle:auth');
Route::post('register/verify', [AuthController::class, 'verifyRegister'])->middleware('throttle:otp');

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:auth');
Route::post('login/verify', [AuthController::class, 'verifyLogin'])->middleware('throttle:otp');

Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
    ->middleware('throttle:otp');

Route::post('otp-check', [AuthController::class, 'checkOtpPassword'])
    ->middleware('throttle:otp');

Route::post('reset-password', [AuthController::class, 'resetPassword'])
    ->middleware(['auth:sanctum' , 'throttle:otp']);

Route::post('google', [SocialAuthController::class, 'loginWithToken'])->middleware('throttle:auth');
Route::get('google/redirect', [SocialAuthController::class, 'redirect'])->middleware('throttle:auth');
Route::get('google/callback', [SocialAuthController::class, 'callback'])->middleware('throttle:auth');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('throttle:refresh');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);
    Route::get('me', [AuthController::class, 'me']);
});