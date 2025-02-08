<?php

namespace App\Providers;

use App\Enums\OrderStatus;
use App\Models\GeneraleSetting;
use App\Models\Order;
use App\Models\User;
use App\Repositories\LanguageRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // share order status with view
        view()->composer(['layouts.app', 'admin.dashboard', 'shop.dashboard'], function ($view) {
            if (request()->is('admin*')) {
                $allOrders = Order::count();
                foreach (OrderStatus::cases() as $status) {
                    // share status wise orders
                    $view->with(Str::camel($status->value), Order::whereOrderStatus($status->value)->count());
                }
            } else {
                $allOrders = auth()->user()->shop?->orders()?->count();
                foreach (OrderStatus::cases() as $status) {
                    // share status wise orders
                    $view->with(Str::camel($status->value), auth()->user()->shop?->orders()?->whereOrderStatus($status->value)->count());
                }
            }
            // share all orders
            $view->with('allOrders', $allOrders);
        });

        view()->composer('*', function ($view) {
            // general settings and business model
            $generaleSetting = GeneraleSetting::first();
            $businessModel = $generaleSetting?->shop_type ?? 'multi';

            // language
            $languages = LanguageRepository::getAll();

            // share languages with view
            $view->with('languages', $languages);

            $users = User::count();

            $seederRun = true;
            // check if users exists
            if ($users > 0) {
                $seederRun = false;
            }

            $storageLink = true;
            // check if storage folder exists
            if (file_exists(public_path('storage'))) {
                $storageLink = false;
            }

            // share seederRun and storageLink
            $view->with('seederRun', $seederRun);
            $view->with('storageLink', $storageLink);

            // share business model and generale setting
            $view->with('generaleSetting', $generaleSetting);
            $view->with('businessModel', $businessModel);
        });

        // use bootstrap 5 for pagination
        Paginator::useBootstrapFive();
    }
}
