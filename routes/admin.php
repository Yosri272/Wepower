<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BusinessSetupController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerNotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryChargeController;
use App\Http\Controllers\Admin\FirebaseController;
use App\Http\Controllers\Admin\GeneraleSettingController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LegalpageController;
use App\Http\Controllers\Admin\MailConfigurationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PusherConfigController;
use App\Http\Controllers\Admin\RiderController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\SMSGatewaySetupController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\ThemeColorController;
use App\Http\Controllers\Admin\WithdrawController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')->group(function () {
    // Login
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login')->middleware('guest');
        Route::post('/login', 'login')->name('login.submit');
    });

    Route::middleware(['auth', 'role:root|admin'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        //banner
        Route::controller(BannerController::class)->group(function () {
            Route::get('/banners', 'index')->name('banner.index');
            Route::get('/banner/create', 'create')->name('banner.create');
            Route::post('/banner/store', 'store')->name('banner.store');
            Route::get('/banner/{banner}/edit', 'edit')->name('banner.edit');
            Route::put('/banner/{banner}/update', 'update')->name('banner.update');
            Route::get('/banner/{banner}/toogle', 'statusToggle')->name('banner.toggle');
            Route::get('/banner/{banner}/destroy', 'destroy')->name('banner.destroy');
        });

        // ads routes
        Route::controller(AdController::class)->group(function () {
            Route::get('/ads', 'index')->name('ad.index');
            Route::get('/ads/create', 'create')->name('ad.create');
            Route::post('/ads/store', 'store')->name('ad.store');
            Route::get('/ads/{ad}/edit', 'edit')->name('ad.edit');
            Route::put('/ads/{ad}/update', 'update')->name('ad.update');
            Route::get('/ads/{ad}/toogle', 'statusToggle')->name('ad.toggle');
            Route::get('/ads/{ad}/destroy', 'destroy')->name('ad.destroy');
        });

        // Shops
        Route::controller(ShopController::class)->group(function () {
            Route::get('/shops', 'index')->name('shop.index');
            Route::get('/shops/create', 'create')->name('shop.create');
            Route::post('/shops/store', 'store')->name('shop.store');
            Route::get('/shops/{shop}/edit', 'edit')->name('shop.edit');
            Route::post('/shops/{shop}/update', 'update')->name('shop.update');
            Route::get('/shops/{shop}', 'show')->name('shop.show');
            Route::get('/shops/{shop}/status-toggle', 'statusToggle')->name('shop.status.toggle');
            Route::get('/shops/{shop}/orders', 'orders')->name('shop.orders');
            Route::get('/shops/{shop}/products', 'products')->name('shop.products');
            Route::get('/shops/{shop}/categories', 'categories')->name('shop.category');
            Route::get('/shops/{shop}/reviews', 'reviews')->name('shop.reviews');
            Route::post('/shops/{shop}/reset-password', 'resetPassword')->name('shop.reset.password');
        });

        // Orders
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders/{status?}', 'index')->name('order.index');
            Route::get('/orders/{order}/show', 'show')->name('order.show');
            Route::get('/orders/{order}/status-change', 'statusChange')->name('order.status.change');
            Route::get('/orders/{order}/payment-status-toggle', 'paymentStatusToggle')->name('order.payment.status.toggle');
        });

        // Categories
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('category.index');

            Route::get('/category/{category}/status-toogle', 'statusToggle')->name('category.status.toggle');
        });

        // Products
        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('product.index');
            Route::get('/products/{product}/approve', 'approve')->name('product.approve');
            Route::get('/product/{product}/show', 'show')->name('product.show');
            Route::delete('/products/{product}/delete', 'destroy')->name('product.destroy');
        });

        //legal page routes
        Route::controller(LegalpageController::class)->group(function () {
            Route::get('/legalpage/{slug}', 'index')->name('legalpage.index');
            Route::get('/legalpage/{slug}/edit', 'edit')->name('legalpage.edit');
            Route::post('/legalpage/{slug}', 'update')->name('legalpage.update');
        });

        //Generate Settings
        Route::controller(GeneraleSettingController::class)->group(function () {
            Route::get('/generale-setting', 'index')->name('generale-setting.index');
            Route::post('/generale-setting', 'update')->name('generale-setting.update');
        });

        // business settings
        Route::controller(BusinessSetupController::class)->group(function () {
            Route::get('/business-setting', 'index')->name('business-setting.index');
            Route::post('/business-setting', 'update')->name('business-setting.update');

            Route::get('/business-shop', 'shop')->name('business-setting.shop');
            Route::post('/business-shop', 'shopUpdate')->name('business-setting.shop.update');

            Route::get('/business-withdraw', 'withdraw')->name('business-setting.withdraw');
            Route::post('/business-withdraw', 'withdrawUpdate')->name('business-setting.withdraw.update');

            Route::get('/business-shop/toggle-pos', 'togglePOS')->name('business-setting.shop.toggle-pos');
            Route::get('/business-shop/toggle-register', 'toggleRegister')->name('business-setting.shop.toggle-register');
        });

        // social links
        Route::controller(SocialLinkController::class)->group(function () {
            Route::get('/social-links', 'index')->name('socialLink.index');
            Route::post('/social-links/{socialLink}', 'update')->name('socialLink.update');
        });

        // theme color
        Route::controller(ThemeColorController::class)->group(function () {
            Route::get('/theme-color', 'index')->name('themeColor.index');
            Route::post('/theme-color', 'update')->name('themeColor.update');
        });

        // delivery charges
        Route::controller(DeliveryChargeController::class)->group(function () {
            Route::get('/delivery-charge', 'index')->name('deliveryCharge.index');
            Route::get('/delivery-charge/create', 'create')->name('deliveryCharge.create');
            Route::post('/delivery-charge/store', 'store')->name('deliveryCharge.store');
            Route::get('/delivery-charge/{deliveryCharge}/edit', 'edit')->name('deliveryCharge.edit');
            Route::put('/delivery-charge/{deliveryCharge}/update', 'update')->name('deliveryCharge.update');
            Route::get('/delivery-charge/{deliveryCharge}/destroy', 'destroy')->name('deliveryCharge.destroy');
        });

        // Coupons
        Route::controller(CouponController::class)->group(function () {
            Route::get('/coupons', 'index')->name('coupon.index');
            Route::get('/coupon/create', 'create')->name('coupon.create');
            Route::post('/coupon/store', 'store')->name('coupon.store');
            Route::get('/coupon/{coupon}/edit', 'edit')->name('coupon.edit');
            Route::put('/coupon/{coupon}/update', 'update')->name('coupon.update');
            Route::get('/coupon/{coupon}/destroy', 'destroy')->name('coupon.destroy');
            Route::get('/coupon/{coupon}/toogle', 'statusToggle')->name('coupon.toggle');
        });

        // Logout
        Route::controller(LoginController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });

        // notification route
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/new-notifications', 'index')->name('new.notification');
            Route::get('/notifications', 'show')->name('notification.show');
            Route::get('/notification/{notification}/read', 'markAsRead')->name('notification.read');
            Route::get('/notification/{notification}/destroy', 'destroy')->name('notification.destroy');
            Route::get('/notification/read-all', 'markAllAsRead')->name('notification.readAll');
        });

        // Pusher Configuration
        Route::controller(PusherConfigController::class)->group(function () {
            Route::get('/pusher-configuration', 'index')->name('pusher.index');
            Route::post('/pusher-configuration', 'update')->name('pusher.update');
        });

        //  mail configuration
        Route::controller(MailConfigurationController::class)->group(function () {
            Route::get('/mail-configuration', 'index')->name('mailConfig.index');
            Route::put('/mail-configuration', 'update')->name('mailConfig.update');
        });

        // payment gateway
        Route::controller(PaymentGatewayController::class)->group(function () {
            Route::get('/payment-gateway', 'index')->name('paymentGateway.index');
            Route::post('/payment-gateway/{paymentGateway}/update', 'update')->name('paymentGateway.update');
            Route::get('/payment-gateway/{paymentGateway}/toggle', 'toggle')->name('paymentGateway.toggle');
        });

        //  SMS Gateway
        Route::controller(SMSGatewaySetupController::class)->group(function () {
            Route::get('/sms-gateway', 'index')->name('sms-gateway.index');
            Route::put('/sms-gateway', 'update')->name('sms-gateway.update');
        });

        // contact us
        Route::controller(ContactUsController::class)->group(function () {
            Route::get('/contact-us', 'index')->name('contactUs.index');
            Route::post('/contact-us/{contactUs?}', 'update')->name('contactUs.update');
        });

        // support route
        Route::controller(SupportController::class)->group(function () {
            Route::get('/supports', 'index')->name('support.index');
            Route::get('/support/{support}/delete', 'delete')->name('support.delete');
        });

        //withdrawal route
        Route::controller(WithdrawController::class)->group(function () {
            Route::get('/withdraws', 'index')->name('withdraw.index');
            Route::get('/withdraw/{withdraw}/show', 'show')->name('withdraw.show');
            Route::post('/withdraw/{withdraw}/update', 'update')->name('withdraw.update');
        });

        //profile
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::put('/profile/update', 'update')->name('profile.update');
            Route::get('/profile/change-password', 'changePassword')->name('profile.change-password');
            Route::put('/profile/change-password/update', 'updatePassword')->name('profile.change-password.update');
        });

        // rider route
        Route::controller(RiderController::class)->group(function () {
            Route::get('/riders', 'index')->name('rider.index');
            Route::get('/riders/create', 'create')->name('rider.create');
            Route::post('/riders/store', 'store')->name('rider.store');
            Route::get('/riders/{user}', 'show')->name('rider.show');
            Route::get('/riders/{user}/edit', 'edit')->name('rider.edit');
            Route::put('/riders/{user}/update', 'update')->name('rider.update');
            Route::get('/riders/{user}/destroy', 'destroy')->name('rider.destroy');
            Route::get('/riders/{user}/toogle', 'statusToggle')->name('rider.toggle');
            Route::post('/riders/{order}/assign-order', 'assignOrder')->name('rider.assign.order');
        });

        // customer route
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/customers', 'index')->name('customer.index');
            Route::get('/customers/create', 'create')->name('customer.create');
            Route::post('/customers/store', 'store')->name('customer.store');
            Route::get('/customers/{user}', 'show')->name('customer.show');
            Route::get('/customers/{user}/edit', 'edit')->name('customer.edit');
            Route::put('/customers/{user}/update', 'update')->name('customer.update');
            Route::get('/customers/{user}/destroy', 'destroy')->name('customer.destroy');
            Route::get('/customers/{user}/toogle', 'statusToggle')->name('customer.toggle');
        });

        // firebase route
        Route::controller(FirebaseController::class)->group(function () {
            Route::get('/firebase-config', 'index')->name('firebase.index');
            Route::post('/firebase-config', 'update')->name('firebase.update');
        });

        // language routes
        Route::controller(LanguageController::class)->group(function () {
            Route::get('/language', 'index')->name('language.index');
            Route::get('/language/create', 'create')->name('language.create');
            Route::post('/language/store', 'store')->name('language.store');
            Route::get('/language/{language}/edit', 'edit')->name('language.edit');
            Route::put('/language/{language}/update', 'update')->name('language.update');
            Route::get('/language/{language}/delete', 'delete')->name('language.delete');
        });

        // Customer Notification route
        Route::controller(CustomerNotificationController::class)->group(function () {
            Route::get('/customer-notifications', 'index')->name('customerNotification.index');
            Route::get('/customer-notification/filter', 'filter')->name('customerNotification.filter');
            Route::post('/customer-notification-send', 'send')->name('customerNotification.send');
        });
    });
});
