<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $shop = auth()->user()->shop;
        $totalOrder = $shop->orders()->count();
        $totalProduct = $shop->products()->count();
        $totalCategories = $shop->categories->count();
        $totalBrand = $shop->brands->count();
        $totalColor = $shop->colors->count();
        $totalSize = $shop->sizes->count();
        $totalUnit = $shop->units->count();

        $orderStatuses = OrderStatus::cases();

        $topSellingProducts = $shop->products()->whereHas('orders')->withCount('orders')->orderBy('orders_count', 'desc')->limit(8)->get();

        $topReviewProducts = $shop->products()->whereHas('reviews')->withAvg('reviews as average_rating', 'rating')->orderBy('average_rating', 'desc')->limit(8)->get();

        $latestOrders = $shop->orders()->latest('id')->limit(8)->get();

        $topFavorites = $shop->products()->whereHas('favorites')->withCount('favorites')->orderBy('favorites_count', 'desc')->limit(8)->get();

        $pendingWithdraw = $shop->withdraws()->where('status', 'pending')->sum('amount');
        $alreadyWithdraw = $shop->withdraws()->where('status', 'approved')->sum('amount');
        $deniedWithddraw = $shop->withdraws()->where('status', 'denied')->sum('amount');

        $totalWithdraw = $pendingWithdraw + $alreadyWithdraw;

        $totalCashCollected = $shop->orders()->where('payment_method', PaymentMethod::CASH->value)->where('order_status', OrderStatus::DELIVERED->value)->sum('total_amount');

        $totalDeliveryCollected = $shop->orders()->where('order_status', OrderStatus::DELIVERED->value)->sum('delivery_charge');

        return view('shop.dashboard', compact('totalOrder', 'totalProduct', 'orderStatuses', 'topSellingProducts', 'topReviewProducts', 'latestOrders', 'topFavorites', 'totalCategories', 'totalBrand', 'totalColor', 'totalSize', 'totalUnit', 'totalWithdraw', 'totalCashCollected', 'totalDeliveryCollected', 'pendingWithdraw', 'alreadyWithdraw', 'deniedWithddraw'));
    }
}
