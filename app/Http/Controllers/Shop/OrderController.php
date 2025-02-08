<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\GeneraleSetting;
use App\Models\Order;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use App\Services\NotificationServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display the order list with filter status.
     */
    public function index($status = null)
    {
        $status = $status ? str_replace('_', ' ', $status) : '';

        $orders = auth()->user()->shop?->orders()->when($status, function ($query) use ($status) {
            $query->where('order_status', $status);
        })->latest('id')->paginate(20);

        return view('shop.order.index', compact('orders', 'status'));
    }

    /**
     * Display the order details.
     */
    public function show($orderId)
    {
        $order = OrderRepository::query()->withoutGlobalScopes()->whereId($orderId)->firstOrFail();

        $orderStatus = OrderStatus::cases();

        $riders = Driver::whereHas('user', function ($query) {
            return $query->where('is_active', true);
        })->get();

        return view('shop.order.show', compact('order', 'orderStatus', 'riders'));
    }

    /**
     * Update the order status.
     */
    public function statusChange(Order $order, Request $request)
    {
        $request->validate(['status' => 'required']);

        $order->update(['order_status' => $request->status]);

        if ($request->status == OrderStatus::DELIVERED->value) {
            $this->updateWalletAndTransaction($order);
        }

        $title = 'Order status updated';
        $message = 'Your order status updated to '.$request->status.' order code: '.$order->prefix.$order->order_code;
        $deviceKeys = $order->customer->user->devices->pluck('key')->toArray();

        try {
            NotificationServices::sendNotification($message, $deviceKeys, $title);
        } catch (\Throwable $th) {}

        $nofify = (object) [
            'title' => $title,
            'content' => $message,
            'user_id' => $order->customer->user_id,
            'type' => 'order',
        ];
        NotificationRepository::storeByRequest($nofify);

        return back()->with('success', __('Order status updated successfully.'));
    }

    /**
     * Update the payment status.
     */
    public function paymentStatusToggle(Order $order)
    {
        if ($order->payment_status->value == PaymentStatus::PAID->value) {
            return back()->with('error', __('When order is paid, payment status cannot be changed.'));
        }
        $order->update(['payment_status' => PaymentStatus::PAID->value]);

        return back()->with('success', __('Payment status updated successfully'));
    }

    public function downloadInvoice($id)
    {
        $order = Order::findOrFail($id);

        $orderCode = '#'.$order->prefix.$order->order_code;

        $qrCode = new EndroidQrCode($orderCode);
        $qrCode->setSize(100);

        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode)->getDataUri();

        $pdf = Pdf::loadView('PDF.invoice', compact('order', 'qrCodeImage'));

        return $pdf->download('invoice-'.$order->prefix.$order->order_code.'.pdf');
    }

    private function updateWalletAndTransaction($order)
    {

        $generaleSetting = GeneraleSetting::first();

        $commission = 0;

        if ($generaleSetting?->commission_charge != 'monthly') {

            if ($generaleSetting?->commission_type != 'fixed') {
                $commission = $order->total_amount * $generaleSetting->commission / 100;
            } else {
                $commission = $generaleSetting->commission ?? 0;
            }
        }

        $order->update([
            'delivery_date' => now(),
            'delivered_at' => now(),
            'payment_status' => PaymentStatus::PAID->value,
            'admin_commission' => $commission,
        ]);

        $wallet = $order->shop->user->wallet;

        WalletRepository::updateByRequest($wallet, $order->payable_amount, 'credit');

        TransactionRepository::storeByRequest($wallet, $commission, 'debit', true, true, 'admin commission', 'order');
    }
}
