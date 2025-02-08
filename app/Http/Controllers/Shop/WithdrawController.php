<?php

namespace App\Http\Controllers\Shop;

use App\Events\AdminProductRequestEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawRequest;
use App\Models\GeneraleSetting;
use App\Models\Notification;
use App\Models\Withdraw;
use App\Repositories\NotificationRepository;
use App\Repositories\WithdrawRepository;
use Carbon\Carbon;

class WithdrawController extends Controller
{
    /**
     * show withdraw
     */
    public function index()
    {
        $withdraws = auth()->user()->shop->withdraws()->paginate(20);

        return view('shop.withdraw.index', compact('withdraws'));
    }

    /**
     * store a new withdraw request
     */
    public function store(WithdrawRequest $request)
    {
        $shop = auth()->user()->shop;

        $pendingWitthdraws = $shop->withdraws()->where('status', 0)->sum('amount');
        $walletBalance = auth()->user()->wallet?->balance;

        $latestPendingWithdraw = $shop->withdraws()->where('status', 0)->latest('id')->first();
        $generaleSetting = GeneraleSetting::first();

        $minWithdrawAmount = $generaleSetting?->min_withdraw ?? 0;

        $isWithdrawable = true;
        $minDayRequest = $generaleSetting?->withdraw_request;

        if ($latestPendingWithdraw && $minDayRequest > 0) {
            $isWithdrawable = $latestPendingWithdraw->created_at->diffInDays(Carbon::now()) >= $minDayRequest;
        }

        if (! $isWithdrawable) {
            return $this->json(__('Sorry! Withdraw request is not available!'), [], 422);
        }

        if (($walletBalance - $pendingWitthdraws) < $request->amount) {
            return $this->json(__('Sorry! Insufficient balance!'), [
                'errors' => [
                    'amount' => ['Insufficient balance'],
                ],
            ], 422);
        }

        // store withdraw request
        $withdraw = WithdrawRepository::storeByRequest($request);

        // admin notification message
        $message = 'Withdraw Request from '.auth()->user()->shop->name;
        try {
            AdminProductRequestEvent::dispatch($message);
        } catch (\Throwable $th) {
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

        return $this->json(__('Withdraw request created successfully!'));
    }

    /**
     * destroy withdraw request
     */
    public function delete(Withdraw $withdraw)
    {
        Notification::where('withdraw_id', $withdraw->id)->delete();
        $withdraw->delete();

        return back()->withSuccess(__('Withdraw request deleted successfully'));
    }
}
