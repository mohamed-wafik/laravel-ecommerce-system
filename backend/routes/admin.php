<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupportTicketController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:admin', 'admin'])->group(function () {


    Route::get('dashboard/summary', [DashboardController::class, 'index']);
    Route::patch('settings', [SettingController::class, 'update']);

    Route::post('media', [MediaController::class, 'store']);
    Route::post('media/bulk', [MediaController::class, 'storeMany']);
    Route::post('media/document', [MediaController::class, 'storeDocument']);
    Route::delete('media', [MediaController::class, 'destroy']);

    Route::apiResource('users', UserController::class);
    Route::post('users/{id}/toggle-active', [UserController::class, 'toggleActive']);
    Route::get('users/{id}/orders', [UserController::class, 'orders']);

    Route::apiResource('categories', CategoryController::class);
    Route::get('categories-tree', [CategoryController::class, 'tree']);
    Route::post('categories/reorder', [CategoryController::class, 'reorder']);
    Route::post('categories/{id}/toggle-active', [CategoryController::class, 'toggleActive']);

    Route::apiResource('brands', BrandController::class);
    Route::post('brands/{id}/toggle-active', [BrandController::class, 'toggleActive']);

    Route::apiResource('products', ProductController::class);
    Route::get('products-low-stock', [ProductController::class, 'lowStock']);
    Route::post('products/{id}/toggle-active', [ProductController::class, 'toggleActive']);
    Route::post('products/{id}/adjust-stock', [ProductController::class, 'adjustStock']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/stats', [OrderController::class, 'stats']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::post(
        '/orders/{id}/refund',
        [OrderController::class, 'refund']
    );
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('payments/{id}', [PaymentController::class, 'show']);

    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('reviews/{id}', [ReviewController::class, 'show']);
    Route::post('reviews/{id}/approve', [ReviewController::class, 'approve']);
    Route::post('reviews/{id}/reject', [ReviewController::class, 'reject']);
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);

    Route::get('support/tickets', [SupportTicketController::class, 'index']);
    Route::get('support/tickets/stats', [SupportTicketController::class, 'stats']);
    Route::get('support/tickets/{id}', [SupportTicketController::class, 'show']);
    Route::post('support/tickets/{id}/reply', [SupportTicketController::class, 'reply']);
    Route::patch('support/tickets/{id}/status', [SupportTicketController::class, 'updateStatus']);

});