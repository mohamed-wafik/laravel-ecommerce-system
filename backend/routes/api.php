<?php

use App\Http\Controllers\Admin\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('settings', [SettingController::class, 'index']);
    Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to the API',
        'version' => '1.0.0',
    ]);
    })->middleware('auth:sanctum');
    Route::prefix('admin')->group(base_path('routes/admin.php'));   // guard applied inside the file
    Route::prefix('auth')->group(base_path('routes/auth.php'));     // throttles applied inside the file
    Route::prefix('guest')->middleware('throttle:guest')->group(base_path('routes/guest.php'));
    Route::prefix('customer')->middleware('auth:sanctum')->group(base_path('routes/customer.php'));
});
require base_path('routes/webhooks.php');                        // no prefix/guard — public