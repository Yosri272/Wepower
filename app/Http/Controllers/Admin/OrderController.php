<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Services\NotificationServices;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a order list with filter status.
     */
    public function index($status = null)
    {
        $status = $status ? str_replace('_', ' ', $status) : '';

        $orders = OrderRepository::query()->when($status, function ($query) use ($status) {
            $query->where('order_status', $status);
        })->latest('id')->paginate(20);

        return view('admin.order.index', compact('orders', 'status'));
    }

    /**
     * Display the order details.
     */
    public function show(Order $order)
    {
        $orderStatus = OrderStatus::cases();

        $riders = Driver::whereHas('user', function ($query) {
            return $query->where('is_active', true);
        })->get();

        return view('admin.order.show', compact('order', 'orderStatus', 'riders'));
    }

    /**
     * Update the order status.
     */
    public function statusChange(Order $order, Request $request)
    {
        $request->validate(['status' => 'required']);

        $order->update(['order_status' => $request->status]);

        $title = 'Order status updated';
        $message = 'Your order status updated to '.$request->status;
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

        $title = 'Payment status updated';
        $message = __('Your payment status updated to paid. order code: ').$order->prefix.$order->order_code;
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

        return back()->with('success', __('Payment status updated successfully'));
    }
}
