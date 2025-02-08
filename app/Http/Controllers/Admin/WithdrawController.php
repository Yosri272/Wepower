<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductApproveEvent;
use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use App\Repositories\NotificationRepository;
use App\Repositories\WithdrawRepository;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * show withdraw
     */
    public function index()
    {
        $status = request('status');
        $withdraws = Withdraw::when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->orderBy('id', 'desc')->paginate(20);

        return view('admin.withdraw.index', compact('withdraws'));
    }

    /**
     * show withdraw details
     */
    public function show(Withdraw $withdraw)
    {
        return view('admin.withdraw.show', compact('withdraw'));
    }

    /**
     * update withdraw request
     */
    public function update(Withdraw $withdraw, Request $request)
    {
        WithdrawRepository::updateWithdraw($withdraw, $request);

        // admin notification message
        $message = 'Withdraw request '.$withdraw->status;
        try {
            ProductApproveEvent::dispatch($message, $withdraw->shop->id);
        } catch (\Throwable $th) {
        }

        $data = (object) [
            'title' => $message,
            'content' => 'Withdraw request '.$withdraw->status.' from admin',
            'url' => '/shop/withdraw',
            'icon' => $request->status == 'approved' ? 'bi-check2-circle' : 'bi-x-octagon-fill',
            'type' => $request->status == 'approved' ? 'success' : 'danger',
            'shop_id' => $withdraw->shop_id,
            'withdraw_id' => $withdraw->id,
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return back()->withSuccess(__('Withdraw request updated successfully'));
    }
}
