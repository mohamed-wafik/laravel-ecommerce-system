<?php

use App\Http\Controllers\auth\FacebookController;
use App\Http\Controllers\auth\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\auth\PorfolioController;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\UserController as ControllersUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);

Route::get("/login",[UserController::class, "showLoginForm"])->name("login");

Route::post("/login",[UserController::class, "login"]);

Route::get("/register",[UserController::class, "showRegisterForm"])->name("register");

Route::post("/register",[UserController::class, "register"]);

Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::get("/logout" , [UserController::class, "logout"])->name("logout");

Route::controller(FacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});

Route::middleware(["auth" , "isAdmin" ])->group(function () {
    Route::prefix("/dashboard")->group(function () {
        Route::get("/", [DashboardController::class, "index"])->name("dashboard.index");
    
        Route::resource('/products', ProductController::class);
    
        Route::get("/orders", [OrderController::class , "index"])->name("dashboard.orders");
        Route::get("/orders/{id}", [OrderController::class , "show"])->name("orders.show");
        Route::put("/orders/{id}/update-status", [OrderController::class , "updateStatus"])->name("orders.update"); 
        Route::get("/orders-export", [OrderController::class , "exportOrder"])->name("orders.export");
        Route::get("/orders/{id}/print", [OrderController::class , "printOrder"])->name("orders.print");
    
        Route::resource('/categories', CategoryController::class);
    
        Route::get("/users", [ControllersUserController::class , "index"])->name("dashboard.users");
        Route::get("/users/{id}", [ControllersUserController::class , "show"])->name("users.show");
        Route::get("/users-export", [ControllersUserController::class , "exportUsers"])->name("users.export");
        Route::put("/users/{id}/role", [ControllersUserController::class , "updateRole"]);
        Route::delete("/users/{id}", [ControllersUserController::class , "destroy"])->name("users.destroy");
    
        Route::get("/portfolio",[PorfolioController::class , "index"])->name("dashboard.settings");
        Route::put("/portfolio/{id}",[PorfolioController::class , "update"])->name("user.update");
        Route::put("/portfolio/{id}/remove-avator", [PorfolioController::class , "removeAvatar"])->name("user.remove_avator");
        Route::put("/portfolio/changePassword/{id}",[PorfolioController::class , "changePassword"])->name("user.changePassword");
    });
});
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');