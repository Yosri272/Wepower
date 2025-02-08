<?php

namespace App\Http\Controllers\API\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\WithdrawResource;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $filterType = request()->filter_type ?? 'this_year';

        $shop = auth()->user()->shop;

        $totalSales = $shop->orders()->where(function ($query) {
            $query->where('order_status', OrderStatus::DELIVERED->value)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        })->sum('total_amount');

        $todayOrders = $shop->orders()->whereDate('created_at', Carbon::today())->count();

        $pendingOrder = $shop->orders()->where('order_status', OrderStatus::PENDING->value)->count();

        $toPickupOrders = $shop->orders()->where(function ($query) {
            $query->whereHas('driverOrder')->where('order_status', OrderStatus::CONFIRM->value)->orWhere('order_status', OrderStatus::PROCESSING->value)->orWhere('order_status', OrderStatus::PICKUP->value);
        })->count();

        $toDeliveryOrders = $shop->orders()->where('order_status', OrderStatus::ON_THE_WAY->value)->count();

        $pendingWithdrow = $shop->withdraws()->where(function ($query) {
            $query->where('status', 'pending');
        })->sum('amount');

        $walletBalence = auth()->user()->wallet->balance - $pendingWithdrow;
        $walletBalence = $walletBalence > 0 ? $walletBalence : 0;

        $latestPendingWithdraw = $shop->withdraws()->where(function ($query) {
            $query->where('status', 'pending');
        })->latest('id')->first();

        if ($filterType === 'last_year') {
            $startDate = now()->subYear()->startOfYear();
            $endDate = now()->subYear()->endOfYear();
        } else {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }

        // Get monthly sale chart
        $monthList = [];
        $valueList = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::create(null, $i, 1);

            $totalAmount = $shop->orders()->where(function ($query) use ($month, $startDate, $endDate) {
                $query->where('order_status', OrderStatus::DELIVERED->value)->whereBetween('created_at', [$month->startOfMonth()->format('Y-m-d'), $month->endOfMonth()->format('Y-m-d')])->whereBetween('created_at', [$startDate, $endDate]);
            })->sum('total_amount');

            $monthList[] = $month->format('M');
            $valueList[] = (float) $totalAmount;
        }

        $maxAmount = max($valueList);
        $minAmount = min($valueList);

        // dd($latestPendingWithdraw);

        return $this->json('Seller dashboard data', [
            'pending_order' => $pendingOrder,
            'to_pickup_order' => $toPickupOrders,
            'today_order' => $todayOrders,
            'to_delivery_order' => $toDeliveryOrders,
            'this_manth_sales' => number_format($totalSales, 2, '.', ','),
            'wallet_balance' => number_format($walletBalence, 2, '.', ','),
            'pending_withdraw' => $latestPendingWithdraw ? WithdrawResource::make($latestPendingWithdraw) : null,
            'max_chart_amount' => (float) number_format($maxAmount, 2, '.', ''),
            'min_chart_amount' => (float) number_format($minAmount, 2, '.', ''),
            'sales_chart_months' => $monthList,
            'sales_chart_values' => $valueList,
        ]);
    }
}
