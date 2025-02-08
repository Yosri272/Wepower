<?php

use App\Http\Controllers\API\Rider\LoginController;
use App\Http\Controllers\API\Rider\NotificationController;
use App\Http\Controllers\API\Rider\OrderController;
use App\Http\Controllers\API\Rider\UserController;
use Illuminate\Support\Facades\Route;

// ==========Route for rider==========
Route::prefix('/rider')->group(function () {

    // auth route
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'login')->name('rider.login');
        Route::post('/register', 'register')->name('rider.register');
        Route::post('/create-password', 'createPassword');
        Route::post('/send-otp', 'sendOTP');
        Route::post('/verify-otp', 'verifyOtp');
        Route::get('/check-user-status', 'checkUserStatus');
    });

    // auth middleware for rider
    Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {

        // user route
        Route::controller(UserController::class)->group(function () {
            Route::get('/details', 'show');
            Route::post('/profile-update', 'update')->name('rider.profile.update');
        });

        // change password
        Route::post('/change-password', [LoginController::class, 'changePassword']);

        // order route
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index');
            Route::get('/orders/details', 'show');
            Route::post('/orders/status-update', 'statusUpdate');
            Route::get('/my-orders', 'statusWiseOrders');
        });

        // notification
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index');
            Route::post('/notifications', 'store');
            Route::post('/notifications/{notification}', 'update');
            Route::delete('/notifications/{notification}', 'delete');
        });

        // logout
        Route::get('/logout', [LoginController::class, 'logout']);
    });
});
