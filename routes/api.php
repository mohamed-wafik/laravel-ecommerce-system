<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix("/auth")->group(function () {

    Route::post("/login",[AuthController::class,"login"]);
    Route::post("/register",[AuthController::class,"register"]);
    
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->middleware('throttle:6,1') // Limit to 6 attempts per minute
        ->name('api.password.email');

    Route::post('/verify-token', [PasswordResetController::class, 'verifyToken'])
        ->middleware('throttle:6,1')
        ->name('api.password.verify');

    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:6,1')
        ->name('api.password.update');
});

Route::get("/products",[ProductController::class , "index"]);
Route::get("/products/{id}",[ProductController::class , "show"]);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("/check",[AuthController::class,"check"]);
    Route::post("/logout",[AuthController::class , "logout"]);
    Route::post("/updata-porfile",[AuthController::class , "updataPorfile"]);
    Route::post("/updata-password",[AuthController::class , "changePassword"]);
    Route::get("/orders",[OrderController::class , "index"]);
    Route::get("/orders/{id}",[OrderController::class , "show"]);
    Route::post("/orders",[OrderController::class , "store"]);

    Route::prefix("/payment")->group(function () {
        Route::post('/create/{order}', [PaymentController::class, 'createPaymentSession'])->name('payment.create');
        Route::get('/success/{order}', [PaymentController::class, 'handleSuccess'])->name('payment.success');
        Route::get('/cancel/{order}', [PaymentController::class, 'handleCancel'])->name('payment.cancel');
        Route::get('/retry/{order}', [PaymentController::class, 'retryPayment'])->name('payment.retry');
    });
});
// Stripe webhook - no CSRF or auth middleware
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');