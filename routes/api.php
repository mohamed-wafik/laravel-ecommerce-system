<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\Route;

Route::post("/login",[AuthController::class,"login"]);
Route::post("/register",[AuthController::class,"register"]);
Route::get("/products",[ProductController::class , "index"]);
Route::get("/products/{id}",[ProductController::class , "show"]);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("/check",[AuthController::class,"check"]);
    Route::post("/logout",[AuthController::class , "logout"]);
    Route::post("/updata-porfile",[AuthController::class , "updataPorfile"]);
    Route::post("/updata-password",[AuthController::class , "changePassword"]);
});