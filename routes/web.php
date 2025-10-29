<?php

use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PorfolioController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController as ControllersUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/login",function() {
    return view('auth.login');
})->name("login");
Route::post("/login",[UserController::class, "login"]);
Route::get("/register",function() {
    return view('auth.register');
})->name("register");
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
    
        Route::get("/orders", function () {
            return view("dashboard.orders.index");
        })->name("dashboard.orders");
    
        Route::resource('/categories', CategoryController::class);
    
        Route::get("/users", [ControllersUserController::class , "index"])->name("dashboard.users");
        Route::get("/users/{id}", [ControllersUserController::class , "show"])->name("users.show");
        Route::put("/users/{id}/role", [ControllersUserController::class , "updateRole"]);
        Route::delete("/users/{id}", [ControllersUserController::class , "destroy"])->name("users.destroy");
    
        Route::get("/portfolio",[PorfolioController::class , "index"])->name("dashboard.settings");
        Route::put("/portfolio/{id}",[PorfolioController::class , "update"])->name("user.update");
        Route::put("/portfolio/{id}/remove-avator", [PorfolioController::class , "removeAvatar"])->name("user.remove_avator");
        Route::put("/portfolio/changePassword/{id}",[PorfolioController::class , "changePassword"])->name("user.changePassword");
    });
});

Route::get('/test-mail', function () {
    try {
        Mail::raw('This is a test email from Laravel using Mailtrap!', function ($message) {
            $message->to('test@example.com')
                    ->subject('Laravel Test Mail');
        });

        return '✅ Email sent! Check your Mailtrap inbox.';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});
// Route::prefix('settings')->group(function () {
//     Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
//     Route::put('/', [SettingsController::class, 'update'])->name('settings.update');
//     Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
//     Route::post('/create-backup', [SettingsController::class, 'createBackup'])->name('settings.create-backup');
// });