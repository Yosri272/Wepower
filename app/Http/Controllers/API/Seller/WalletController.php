<?php

namespace App\Http\Controllers\API\Seller;

use App\Enums\OrderStatus;
use App\Events\AdminProductRequestEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawRequest;
use App\Http\Resources\WithdrawResource;
use App\Models\GeneraleSetting;
use App\Repositories\NotificationRepository;
use App\Repositories\WithdrawRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * get seller wallet
     *
     * @return json
     */
    public function index(Request $request)
    {
        $filterType = $request->filter_type ?? 'this_month';

        $shop = auth()->user()->shop;

        $orders = $shop->orders()->where('order_status', OrderStatus::DELIVERED->value)->when($filterType == 'today', function ($query) {
            return $query->where(function ($query) {
                $query->whereDate('created_at', Carbon::today());
            });
        })->when($filterType == 'this_week', function ($query) {
            return $query->where(function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            });
        })->when($filterType == 'this_month', function ($query) {
            return $query->where(function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            });
        })->when($filterType == 'this_year', function ($query) {
            return $query->where(function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            });
        })->when($filterType == 'last_week', function ($query) {
            return $query->where(function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()->subWeek(1)]);
            });
        })->when($filterType == 'last_month', function ($query) {
            return $query->where(function ($query) {
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
            });
        })->when($filterType == 'last_year', function ($query) {
            return $query->where(function ($query) {
                $query->whereYear('created_at', Carbon::now()->subYear()->year);
            });
        });

        $totalAmount = $orders->sum('total_amount');
        $discount = $orders->sum('coupon_discount');

        $totalSales = $totalAmount - $discount;

        $commission = $orders->sum('admin_commission');

        $profit = $totalSales - $commission;

        $pendingWitthdraws = $shop->withdraws()->where('status', 0)->sum('amount');

        $withdrawableAmount = auth()->user()->wallet->balance - $pendingWitthdraws;

        $totalOrders = $shop->orders()->where('order_status', OrderStatus::DELIVERED->value)->get();

        $lifetimeSales = $totalOrders->sum('total_amount') - $totalOrders->sum('coupon_discount');

        $latestPendingWithdraw = $shop->withdraws()->where('status', 0)->latest('id')->first();

        $generaleSetting = GeneraleSetting::first();

        $minWithdrawAmount = $generaleSetting?->min_withdraw ?? 0;

        $isWithdrawable = true;
        $minDayRequest = $generaleSetting?->withdraw_request;

        if ($latestPendingWithdraw && $minDayRequest > 0) {
            $isWithdrawable = $latestPendingWithdraw->created_at->diffInDays(Carbon::now()) >= $minDayRequest;
        }

        return $this->json('wallet details', [
            'total_sales' => number_format($totalSales, 2, '.', ','),
            'commission' => number_format($commission, 2, '.', ','),
            'profit' => number_format($profit, 2, '.', ','),
            'lifetime_sales' => number_format($lifetimeSales, 2, '.', ','),
            'withdrawable_amount' => number_format($withdrawableAmount > 0 ? $withdrawableAmount : 0, 2, '.', ','),
            'growth_percentage' => '+2.5%',
            'pending_withdraw' => $latestPendingWithdraw ? WithdrawResource::make($latestPendingWithdraw) : null,
            'min_withdraw_amount' => (float) $minWithdrawAmount,
            'is_withdrawable' => (bool) $isWithdrawable,
        ]);
    }

    /**
     * withdraw history
     *
     * @return json
     */
    public function history(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 10;
        $skip = ($page * $perPage) - $perPage;

        $filterType = $request->filter_type ?? 'this_month';

        $shop = auth()->user()->shop;

        $withdraws = $shop->withdraws()->when($filterType == 'today', function ($query) {
            return $query->where(function ($query) {
                $query->whereDate('created_at', Carbon::today());
            });
        })->when($filterType == 'this_week', function ($query) {
            return $query->where(function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            });
        })->when($filterType == 'this_month', function ($query) {
            return $query->where(function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            });
        })->when($filterType == 'this_year', function ($query) {
            return $query->where(function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            });
        })->when($filterType == 'last_week', function ($query) {
            return $query->where(function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()->subWeek(1)]);
            });
        })->when($filterType == 'last_month', function ($query) {
            return $query->where(function ($query) {
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
            });
        })->when($filterType == 'last_year', function ($query) {
            return $query->where(function ($query) {
                $query->whereYear('created_at', Carbon::now()->subYear()->year);
            });
        })->orderBy('created_at', 'desc');

        $total = $withdraws->count();

        $withdraws = $withdraws->skip($skip)->take($perPage)->get();

        return $this->json('withdraw history', [
            'total' => $total,
            'withdraws' => WithdrawResource::collection($withdraws),
        ]);
    }

    /**
     * store a new withdraw request
     *
     * @return json
     */
    public function withdraw(WithdrawRequest $request)
    {
        $shop = auth()->user()->shop;
        $pendingWitthdraws = $shop->withdraws()->where('status', 'pending')->sum('amount');
        $walletBalance = auth()->user()->wallet?->balance;

        $latestPendingWithdraw = $shop->withdraws()->where(function ($query) {
            $query->where('status', 'pending');
        })->latest('id')->first();

        $generaleSetting = GeneraleSetting::first();

        $isWithdrawable = true;
        $minDayRequest = $generaleSetting?->withdraw_request;

        if ($latestPendingWithdraw && $minDayRequest > 0) {
            $isWithdrawable = $latestPendingWithdraw->created_at->diffInDays(Carbon::now()) >= $minDayRequest;
        }

        if (! $isWithdrawable) {
            return $this->json('Sorry! Withdraw request is not available', [], 422);
        }

        // check balance
        if (($walletBalance - $pendingWitthdraws) < $request->amount) {
            return $this->json('Insufficient Balance', [], 422);
        }

        // store withdraw request
        $withdraw = WithdrawRepository::storeByRequest($request);

        // admin notification message
        $message = 'Withdraw Request from '.auth()->user()->shop->name;
        try {
            AdminProductRequestEvent::dispatch($message);
        } catch (\Throwable $th) {
            //throw $th;
        }

        $data = (object) [
            'title' => $message,
            'content' => 'New Withdraw Request created from '.auth()->user()->shop->name.' shop.',
            'url' => '/admin/withdraw/'.$withdraw->id.'/show',
            'icon' => 'bi-wallet2',
            'type' => 'success',
            'withdraw_id' => $withdraw->id,
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return $this->json('Withdraw Request Created successfully', [
            'withdraw' => WithdrawResource::make($withdraw),
        ]);
    }
}
