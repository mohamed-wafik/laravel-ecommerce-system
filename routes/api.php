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

    Route::post("/login",[AuthController::class,"login"])->middleware(`throttle:6,1`);
    Route::post("/register",[AuthController::class,"register"])->middleware("throttle:6,1");

    Route::get("/check",[AuthController::class,"check"])->middleware("auth:sanctum");
    Route::post("/logout",[AuthController::class,"logout"])->middleware("auth:sanctum");
    
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->middleware('throttle:6,1')
        ->name('api.password.email');

    Route::post('/verify-token', [PasswordResetController::class, 'verifyToken'])
        ->middleware('throttle:6,1')
        ->name('api.password.verify');

    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:6,1')
        ->name('api.password.update');
});


Route::middleware(['auth:sanctum'])->group(function () {

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
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])
    ->withoutMiddleware(['auth:sanctum', 'throttle'])
    ->name('stripe.webhook');