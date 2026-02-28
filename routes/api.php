<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\CartController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\api\CategoriesController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\PaymentController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix("/auth")->group(function () {

    Route::prefix('/password')->group(function () {
        Route::post('/request-otp',  [PasswordResetController::class, 'sendOtp']);   
        Route::post('/verify-otp',   [PasswordResetController::class, 'verifyOtp']);    
        Route::post('/reset',        [PasswordResetController::class, 'resetPassword']);        
    });

    Route::post("/login",[AuthController::class,"login"])->middleware(`throttle:6,1`);
    Route::post("/register",[AuthController::class,"register"])->middleware("throttle:6,1");

    Route::get("/check",[AuthController::class,"check"])->middleware("auth:sanctum");
    Route::post("/logout",[AuthController::class,"logout"])->middleware("auth:sanctum");
    
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::post("/updata-porfile",[AuthController::class , "updataPorfile"]);
    Route::post("/updata-password",[AuthController::class , "changePassword"]);

    Route::get("/orders",[OrderController::class , "index"]);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::patch('/orders/{orderId}/payment-status', [OrderController::class, 'updatePaymentStatus']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);

    // Payment
    Route::get('/payment/public-key', [PaymentController::class, 'getPublicKey']);
    Route::post('/payment/create-checkout-session', [PaymentController::class, 'createCheckoutSession']);
    Route::post('/payment/verify', [PaymentController::class, 'verifyPayment']);
    Route::post('/payment/cancel', [PaymentController::class, 'handleCancel']);


    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    Route::post('/reviews', [ReviewController::class, 'store']);
});

Route::get("/products",[ProductController::class , "index"]);
Route::get("/products/{id}",[ProductController::class , "show"]);
Route::get('/product/top-deal', [ProductController::class, 'getTopDeal']);
Route::get('/product/{id}/reviews', [ReviewController::class, 'productReviews']);

Route::get("/categories", [CategoriesController::class , "index"]);
// Stripe webhook - no CSRF or auth middleware
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])
    ->name('stripe.webhook');