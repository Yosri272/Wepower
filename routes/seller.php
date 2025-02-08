<?php

use App\Http\Controllers\API\Seller\BannerController;
use App\Http\Controllers\API\Seller\DashboardController;
use App\Http\Controllers\API\Seller\LoginController;
use App\Http\Controllers\API\Seller\NotificationController;
use App\Http\Controllers\API\Seller\OrderController;
use App\Http\Controllers\API\Seller\UserController;
use App\Http\Controllers\API\Seller\WalletController;
use Illuminate\Support\Facades\Route;

// ==========Route for seller==========
Route::prefix('/seller')->group(function () {

    // auth route
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'login')->name('seller.login');
        Route::post('/registration', 'register')->name('seller.register');
        Route::post('/forgot-password', 'forgotPassword');
        Route::post('/send-otp', 'sendOTP');
        Route::post('/verify-otp', 'verifyOtp');
        Route::get('/check-user-status', 'checkUserStatus');
    });

    // auth middleware for rider
    Route::middleware(['auth:sanctum', 'role:shop'])->group(function () {

        // user route
        Route::controller(UserController::class)->group(function () {
            Route::get('/details', 'show');
            Route::post('/user-update', 'updateProfile');
            Route::post('/shop-update', 'shopUpdate');
            Route::post('/shop-setting-update', 'shopSettingUpdate');
        });

        // banner route
        Route::controller(BannerController::class)->group(function () {
            Route::get('/banners', 'index');
            Route::post('/banners/store', 'store');
            Route::post('/banners/update', 'update');
            Route::delete('/banners/{banner}', 'destroy');
        });

        // dashboard route
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // change password
        Route::post('/change-password', [LoginController::class, 'changePassword']);

        // order route
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index');
            Route::get('/orders/details', 'show');
            Route::post('/orders/status-update', 'update');
        });

        // wallet route
        Route::controller(WalletController::class)->group(function () {
            Route::get('/wallet', 'index');
            Route::get('/wallet/history', 'history');
            Route::post('/wallet/withdraw', 'withdraw');
        });

        // notification
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index');
            Route::post('/notifications/{notification}', 'update');
            Route::delete('/notifications/{notification}', 'delete');
        });

        // logout
        Route::get('/logout', [LoginController::class, 'logout']);
    });
});
