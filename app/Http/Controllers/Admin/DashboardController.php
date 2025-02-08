<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\Withdraw;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $totalShop = Shop::count();
        $totalOrder = Order::count();
        $totalCustomer = Customer::count();
        $totalProduct = Product::count();

        $totalCategories = Category::count();

        $orderStatuses = OrderStatus::cases();

        $topCustomers = Customer::withCount('orders')->orderBy('orders_count', 'desc')->limit(8)->get();

        $topSellingProducts = Product::withCount('orders')->orderBy('orders_count', 'desc')->limit(8)->get();

        $topReviewProducts = Product::withAvg('reviews as average_rating', 'rating')->orderBy('average_rating', 'desc')->limit(8)->get();

        $topShops = Shop::withCount('orders')->orderBy('orders_count', 'desc')->withAvg('reviews as average_rating', 'rating')->orderBy('average_rating', 'desc')->limit(8)->get();

        $latestOrders = Order::latest('id')->limit(6)->get();

        $topFavorites = Product::whereHas('favorites')->withCount('favorites')->orderBy('favorites_count', 'desc')->limit(8)->get();

        $pendingWithdraw = Withdraw::where('status', 'pending')->sum('amount');
        $alreadyWithdraw = Withdraw::where('status', 'approved')->sum('amount');
        $deniedWithddraw = Withdraw::where('status', 'denied')->sum('amount');

        $totalCommission = Transaction::where('is_commission', true)->sum('amount');

        return view('admin.dashboard', compact('totalShop', 'totalOrder', 'totalCustomer', 'totalProduct', 'orderStatuses', 'topCustomers', 'topSellingProducts', 'topReviewProducts', 'topShops', 'latestOrders', 'topFavorites', 'pendingWithdraw', 'alreadyWithdraw', 'deniedWithddraw', 'totalCommission', 'totalCategories'));
    }
}
