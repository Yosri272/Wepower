<?php

use App\Http\Controllers\Shop\Auth\LoginController;
use App\Http\Controllers\Shop\BannerController;
use App\Http\Controllers\Shop\BrandController;
use App\Http\Controllers\Shop\BulkProductExportController;
use App\Http\Controllers\Shop\BulkProductImportController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\ColorController;
use App\Http\Controllers\Shop\DashboardController;
use App\Http\Controllers\Shop\GalleryController;
use App\Http\Controllers\Shop\GiftController;
use App\Http\Controllers\Shop\NotificationController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\POSController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\SizeController;
use App\Http\Controllers\Shop\SubCategoryController;
use App\Http\Controllers\Shop\UnitController;
use App\Http\Controllers\Shop\VoucherController;
use App\Http\Controllers\Shop\WithdrawController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::name('shop.')->group(function () {

    Route::controller(OrderController::class)->group(function () {
        Route::get('/download-invoice/{id}', 'downloadInvoice')->name('download-invoice');
    });

    // Login
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login')->middleware('guest');
        Route::post('/login', 'login')->name('login.submit');
    });

    Route::middleware(['authShop', 'role:shop'])->group(function () {

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
            Route::get('/category/create', 'create')->name('category.create');
            Route::post('/category/store', 'store')->name('category.store');
            Route::get('/category/{category}/edit', 'edit')->name('category.edit');
            Route::put('/category/{category}/update', 'update')->name('category.update');
            Route::delete('/category/{category}/destroy', 'destroy')->name('category.destroy');
            Route::get('/category/{category}/toogle', 'statusToggle')->name('category.toggle');
        });

        //sub categories route
        Route::controller(SubCategoryController::class)->group(function () {
            Route::get('/subcategories', 'index')->name('subcategory.index');
            Route::get('/subcategory/create', 'create')->name('subcategory.create');
            Route::post('/subcategory/store', 'store')->name('subcategory.store');
            Route::get('/subcategory/{subCategory}/edit', 'edit')->name('subcategory.edit');
            Route::put('/subcategory/{subCategory}/update', 'update')->name('subcategory.update');
            Route::delete('/subcategory/{subCategory}/destroy', 'destroy')->name('subcategory.destroy');
            Route::get('/subcategory/{subCategory}/toogle', 'statusToggle')->name('subcategory.toggle');
        });

        //brand
        Route::controller(BrandController::class)->group(function () {
            Route::get('/brands', 'index')->name('brand.index');
            Route::post('/brand/store', 'store')->name('brand.store');
            Route::put('/brand/{brand}/update', 'update')->name('brand.update');
            Route::delete('/brand/{brand}/destroy', 'destroy')->name('brand.destroy');
            Route::get('/brand/{brand}/toogle', 'statusToggle')->name('brand.toggle');
        });

        //color
        Route::controller(ColorController::class)->group(function () {
            Route::get('/colors', 'index')->name('color.index');
            Route::post('/color/store', 'store')->name('color.store');
            Route::put('/color/{color}/update', 'update')->name('color.update');
            Route::delete('/color/{color}/destroy', 'destroy')->name('color.destroy');
            Route::get('/color/{color}/toogle', 'statusToggle')->name('color.toggle');
        });

        //size
        Route::controller(SizeController::class)->group(function () {
            Route::get('/sizes', 'index')->name('size.index');
            Route::post('/size/store', 'store')->name('size.store');
            Route::put('/size/{size}/update', 'update')->name('size.update');
            Route::delete('/size/{size}/destroy', 'destroy')->name('size.destroy');
            Route::get('/size/{size}/toogle', 'statusToggle')->name('size.toggle');
        });

        //unit
        Route::controller(UnitController::class)->group(function () {
            Route::get('/units', 'index')->name('unit.index');
            Route::post('/unit/store', 'store')->name('unit.store');
            Route::put('/unit/{unit}/update', 'update')->name('unit.update');
            Route::delete('/unit/{unit}/destroy', 'destroy')->name('unit.destroy');
            Route::get('/unit/{unit}/toogle', 'statusToggle')->name('unit.toggle');
        });

        // Products
        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('product.index');
            Route::get('/product/create', 'create')->name('product.create');
            Route::post('/product/store', 'store')->name('product.store');
            Route::get('/product/{product}/edit', 'edit')->name('product.edit');
            Route::put('/product/{product}/update', 'update')->name('product.update');
            Route::get('/product/{product}/show', 'show')->name('product.show');
            Route::get('/product/{product}/toogle', 'statusToggle')->name('product.toggle');
            Route::delete('/product/{product}/destroy', 'destroy')->name('product.destroy');
            Route::get('/product/{product}/thumbnail/{media}/delete', 'thumbnailDestroy')->name('product.remove.thumbnail');
            Route::get('/product/{product}/generate-barcode', 'generateBarcode')->name('product.barcode');
        });

        //profile
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::put('/profile/update', 'update')->name('profile.update');
            Route::get('/profile/change-password', 'changePassword')->name('profile.change-password');
            Route::put('/profile/change-password/update', 'updatePassword')->name('profile.change-password.update');
        });

        // Coupon Vouchers
        Route::controller(VoucherController::class)->group(function () {
            Route::get('/vouchers', 'index')->name('voucher.index');
            Route::get('/voucher/create', 'create')->name('voucher.create');
            Route::post('/voucher/store', 'store')->name('voucher.store');
            Route::get('/voucher/{coupon}/edit', 'edit')->name('voucher.edit');
            Route::put('/voucher/{coupon}/update', 'update')->name('voucher.update');
            Route::get('/voucher/{coupon}/destroy', 'destroy')->name('voucher.destroy');
            Route::get('/voucher/{coupon}/toogle', 'statusToggle')->name('voucher.toggle');
        });

        // Logout
        Route::controller(LoginController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });

        // notification
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/new-notifications', 'index')->name('new.notification');
            Route::get('/notifications', 'show')->name('notification.show');
            Route::get('/notification/{notification}/read', 'markAsRead')->name('notification.read');
            Route::get('/notification/{notification}/destroy', 'destroy')->name('notification.destroy');
            Route::get('/notification/read-all', 'markAllAsRead')->name('notification.readAll');
        });

        //withdrawal route
        Route::controller(WithdrawController::class)->group(function () {
            Route::get('/withdraw', 'index')->name('withdraw.index');
            Route::post('/withdraw/store', 'store')->name('withdraw.store');
            Route::get('/withdraw/{withdraw}/delete', 'delete')->name('withdraw.delete');
            Route::get('/withdraw/{withdraw}/show', 'show')->name('withdraw.show');
        });
        //bulk product route
        Route::controller(BulkProductImportController::class)->group(function () {
            Route::get('/bulk-product-import', 'index')->name('bulk-product-import.index');
            Route::post('/bulk-product-import/store', 'store')->name('bulk-product-import.store');
            Route::get('/bulk-product-format-export', 'formatExport')->name('bulk-product-import.formatExport');
            Route::post('/bulk-product-import/export', 'export')->name('bulk-product-import.export');
        });

        //bulk product export route
        Route::controller(BulkProductExportController::class)->group(function () {
            Route::get('/bulk-product-export', 'index')->name('bulk-product-export.index');

            Route::post('/bulk-product-export/export', 'export')->name('bulk-product-export.export');

            Route::get('/bulk-product-export/demo', 'demoExport')->name('bulk-product-export.demo');
        });

        // gallery route
        Route::controller(GalleryController::class)->group(function () {
            Route::get('/gallery', 'index')->name('gallery.index');
            Route::get('/gallery/create', 'create')->name('gallery.create');
            Route::post('/gallery/store', 'store')->name('gallery.store');
        });

        // gift route
        Route::controller(GiftController::class)->group(function () {
            Route::get('/gift', 'index')->name('gift.index');
            Route::get('/gift/create', 'create')->name('gift.create');
            Route::post('/gift/store', 'store')->name('gift.store');
            Route::get('/gift/{gift}/edit', 'edit')->name('gift.edit');
            Route::put('/gift/{gift}/update', 'update')->name('gift.update');
            Route::get('/gift/{gift}/destroy', 'destroy')->name('gift.destroy');
            Route::get('/gift/{gift}/toogle', 'statusToggle')->name('gift.toggle');
        });

        // POS routes
        Route::controller(POSController::class)->group(function () {
            Route::get('/pos', 'index')->name('pos.index');
            Route::get('/pos/sales', 'sales')->name('pos.sales');
            Route::get('/pos/draft', 'draft')->name('pos.draft');

            // others
            Route::get('/pos/{order}/invoice', 'invoice')->name('pos.invoice');
            Route::post('/fetch-products', 'getProduct')->name('pos.product');
            Route::post('/add-to-cart', 'addToCart')->name('pos.addToCart');
            Route::post('/fetch-cart', 'getCart')->name('pos.getCart');
            Route::post('/update-cart', 'updateCart')->name('pos.updateCart');
            Route::post('/remove-cart', 'removeCart')->name('pos.removeCart');
            Route::post('/apply-coupon', 'applyCoupon')->name('pos.applyCoupon');
            Route::post('/remove-coupon', 'removeCoupon')->name('pos.removeCoupon');
            Route::post('/store-order', 'storeOrder')->name('pos.submitOrder');
            Route::post('/customer-store', 'storeCustomer')->name('pos.customerStore');
        });
    });
});
