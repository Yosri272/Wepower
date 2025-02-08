<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\GiftController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LegalPageController;
use App\Http\Controllers\API\MasterController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\SubCategoryController;
use App\Http\Controllers\API\SupportController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/registration', 'register');
    Route::post('/login', 'login');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::post('/send-otp', 'resendOTP');
    Route::post('/verify-otp', 'verifyOtp');
    Route::post('/reset-password', 'resetPassword');
});

//legal page route
Route::controller(LegalPageController::class)->group(function () {
    Route::get('/legal-pages/{slug}', 'index');
    Route::get('/contact-us', 'contactUs');
});

//support route
Route::controller(SupportController::class)->group(function () {
    Route::post('/support', 'store');
});

//master route
Route::controller(MasterController::class)->group(function () {
    Route::get('/master', 'index');
});

//home route
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index');
    Route::get('/recently-views', 'recentlyViews');
});

//Banner route
Route::controller(BannerController::class)->group(function () {
    Route::get('/banners', 'index');
});

//category route
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::get('/category-products', 'show');
});

//sub category route
Route::controller(SubCategoryController::class)->group(function () {
    Route::get('/sub-categories', 'index');
});

//product route
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/product-details', 'show');
});

//review route
Route::controller(ReviewController::class)->group(function () {
    Route::get('/reviews', 'index');
});

//shop route
Route::controller(ShopController::class)->group(function () {
    Route::get('/shops', 'index');
    Route::get('/shops/top', 'topShops');
    Route::get('/shop-categories', 'shopCategory');
    Route::get('/shops/popular-products', 'popularProducts');
    Route::get('/shops/{shop}', 'show');
});

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {

    //user route
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'index');
        Route::post('/update-profile', 'update');
        Route::post('/change-password', 'changePassword');
    });

    //recently view route
    Route::controller(HomeController::class)->group(function () {
        Route::get('/recently-views', 'recentlyViews');
    });

    //favorite add or remove route
    Route::controller(ProductController::class)->group(function () {
        Route::post('/favorite-add-or-remove', 'addFavorite');
        Route::get('/favorite-products', 'favoriteProducts');
        Route::post('/product-review', 'storeReview');
    });

    //order route
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::post('/place-order', 'store');
        Route::get('/order-details', 'show');
        Route::post('/orders/cancel', 'cancel');
        Route::post('/place-order/again', 'reOrder');
        Route::get('/order-payment/{order}/{paymentMethod?}', 'payment');
        Route::post('buy-now/place-order', 'buyNowStore');
        Route::post('/buy-now', 'buyNow');
    });

    //order route for version 1
    Route::controller(OrderController::class)->prefix('/v1')->group(function () {
        Route::post('/place-order', 'storeV1');
    });

    //coupon route
    Route::controller(CouponController::class)->group(function () {
        Route::get('/get-vouchers', 'index');
        Route::post('/vouchers-collect', 'store')->name('voucher.collect');
        Route::get('/get-collected-vouchers', 'collectedVouchers');
        Route::post('/apply-voucher', 'applyVoucher')->name('voucher.apply');
        Route::post('/coupons/apply', 'getDiscount');
    });

    //address route
    Route::controller(AddressController::class)->group(function () {
        Route::get('/addresses', 'index');
        Route::post('/address/store', 'store');
        Route::post('/address/{address}/update', 'update');
        Route::delete('/address/{address}/delete', 'destroy');
    });

    //cart route
    Route::controller(CartController::class)->group(function () {
        Route::get('/carts', 'index');
        Route::post('/cart/store', 'store');
        Route::post('/cart/increment', 'increment');
        Route::post('/cart/decrement', 'decrement');
        Route::post('/cart/delete', 'destroy');
        Route::post('/cart/checkout', 'checkout');
    });

    // gift route
    Route::controller(GiftController::class)->group(function () {
        Route::get('/gifts', 'index');
        Route::post('/gift/store', 'store');
        Route::post('/gift/update', 'update');
        Route::delete('/gift/delete', 'destroy');
    });

});
