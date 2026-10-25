<?php

use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\SupportTicketController;
use Illuminate\Support\Facades\Route;

Route::get('profile', [ProfileController::class, 'show']);
Route::patch('profile', [ProfileController::class, 'update']);
Route::post('profile/change-password', [ProfileController::class, 'changePassword']);
Route::post('profile/change-email', [ProfileController::class, 'changeEmail']);
Route::post('profile/verify-email-change', [ProfileController::class, 'verifyEmailChange']);
Route::delete('profile', [ProfileController::class, 'destroy']);
Route::delete('profile/remove-avatar', [ProfileController::class, 'removeAvatar']);

Route::apiResource('addresses', AddressController::class)->except(['show']);

Route::post("reviews/{productId}", [AdminReviewController::class, 'store']);

Route::get('cart', [CartController::class, 'show']);
Route::post('cart/items', [CartController::class, 'addItem']);
Route::patch('cart/items/{itemId}', [CartController::class, 'updateItem']);
Route::delete('cart/items/{itemId}', [CartController::class, 'removeItem']);
Route::delete("cart/clear",[CartController::class, 'clear']);

Route::apiResource('/address', AddressController::class)
    ->except(['show']);
    
Route::get('support/tickets', [SupportTicketController::class, 'index']);
Route::post('support/tickets', [SupportTicketController::class, 'store']);
Route::get('support/tickets/{id}', [SupportTicketController::class, 'show']);
Route::post('support/tickets/{id}/reply', [SupportTicketController::class, 'reply']);


Route::apiResource("orders",OrderController::class)->only(['index','show']);

Route::post('checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:checkout');