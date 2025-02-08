<?php

namespace App\Http\Controllers\API\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderIdRequest;
use App\Http\Requests\StatusUpdateRequest;
use App\Http\Resources\SellerOrderResource;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Services\NotificationServices;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 15;
        $skip = ($page * $perPage) - $perPage;

        $search = $request->search;

        $string = $search;

        // remove # and 2 letters from search
        if (preg_match('/\d/', $string) && ! preg_match('/\s/', $string) && strpos($string, '#') !== false) {
            $search = substr($string, 3);
        }

        $startDate = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d') : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

        $filterType = $request->filter_type ?? null;

        $orderStatus = $request->order_status ?? null;
        $shop = auth()->user()->shop;

        $orders = $shop->orders()->when($search, function ($query) use ($search) {
            return $query->where('order_code', 'like', "%$search%")->orWhereHas('customer', function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    return $query->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->orWhere('phone', 'like', "%$search%");
                });
            });
        })->when($startDate, function ($query) use ($startDate, $endDate) {
            return $query->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])->orWhereBetween('updated_at', [$startDate, $endDate]);
            });
        })->when($filterType == 'today', function ($query) {
            return $query->where(function ($query) {
                $query->whereDate('created_at', Carbon::today())->orWhereDate('updated_at', Carbon::today());
            });
        })->when($filterType == 'this_week', function ($query) {
            return $query->where(function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orWhereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            });
        })->when($filterType == 'this_month', function ($query) {
            return $query->where(function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month)->orWhereMonth('updated_at', Carbon::now()->month);
            });
        })->when($filterType == 'this_year', function ($query) {
            return $query->where(function ($query) {
                $query->whereYear('created_at', Carbon::now()->year)->orWhereYear('updated_at', Carbon::now()->year);
            });
        })->when($filterType == 'last_week', function ($query) {
            return $query->where(function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()->subWeek(1)])->orWhereBetween('updated_at', [Carbon::now()->subWeek(), Carbon::now()->subWeek(1)]);
            });
        })->when($filterType == 'last_month', function ($query) {
            return $query->where(function ($query) {
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)->orWhereMonth('updated_at', Carbon::now()->subMonth()->month);
            });
        })->when($filterType == 'last_year', function ($query) {
            return $query->where(function ($query) {
                $query->whereYear('created_at', Carbon::now()->subYear()->year)->orWhereYear('updated_at', Carbon::now()->subYear()->year);
            });
        })->when($orderStatus == 'pending', function ($query) {
            return $query->where('order_status', OrderStatus::PENDING->value);
        })->when($orderStatus == 'confirm', function ($query) {
            return $query->where('order_status', OrderStatus::CONFIRM->value);
        })->when($orderStatus == 'to_pickup', function ($query) {
            return $query->whereHas('driverOrder')->where(function ($query) {
                $query->where('order_status', OrderStatus::CONFIRM->value)->orWhere('order_status', OrderStatus::PROCESSING->value);
            });
        })->when($orderStatus == 'to_delivery', function ($query) {
            return $query->where(function ($query) {
                $query->where('order_status', OrderStatus::ON_THE_WAY->value)->orWhere('order_status', OrderStatus::PICKUP->value);
            });
        })->when($orderStatus == 'delivered', function ($query) {
            return $query->where('order_status', OrderStatus::DELIVERED->value);
        });

        $total = $orders->count();

        $driverOrders = $orders->skip($skip)->take($perPage)->get();

        $totalOrders = $shop->orders->count();

        $pending = $shop->orders()->where(function ($query) {
            return $query->where('order_status', OrderStatus::PENDING->value);
        })->count();

        $confirm = $shop->orders()->where('order_status', OrderStatus::CONFIRM->value)->count();

        $toPickup = $shop->orders()->whereHas('driverOrder')->where(function ($query) {
            return $query->where('order_status', OrderStatus::CONFIRM->value)->orWhere('order_status', OrderStatus::PROCESSING->value);
        })->count();

        $toDelivery = $shop->orders()->where(function ($query) {
            return $query->where('order_status', OrderStatus::PICKUP->value)->orWhere('order_status', OrderStatus::ON_THE_WAY->value);
        })->count();

        $delivered = $shop->orders()->where(function ($query) {
            return $query->where('order_status', OrderStatus::DELIVERED->value);
        })->count();

        $totalOrders = $shop->orders->count();

        $statusArray = [
            (object) [
                'name' => 'All',
                'value' => $totalOrders,
                'status' => 'all',
            ],
            (object) [
                'name' => 'Pending',
                'value' => $pending,
                'status' => 'pending',
            ],
            (object) [
                'name' => 'Confirm',
                'value' => $confirm,
                'status' => 'confirm',
            ],
            (object) [
                'name' => 'To Pickup',
                'value' => $toPickup,
                'status' => 'to_pickup',
            ],
            (object) [
                'name' => 'To Delivery',
                'value' => $toDelivery,
                'status' => 'to_delivery',
            ],
            (object) [
                'name' => 'Delivered',
                'value' => $delivered,
                'status' => 'delivered',
            ],
        ];

        return $this->json('all order list', [
            'total_items' => $total,
            'status_orders' => $statusArray,
            'orders' => SellerOrderResource::collection($driverOrders),
        ]);
    }

    // show order details
    public function show(OrderIdRequest $request)
    {
        $order = OrderRepository::find($request->order_id);

        return $this->json('Order details', [
            'order' => SellerOrderResource::make($order),
        ]);
    }

    // status update
    public function update(StatusUpdateRequest $request)
    {
        $order = OrderRepository::find($request->order_id);

        if (! $order) {
            return $this->json('Sorry, this order is not found', [], 422);
        }

        $orderStatus = $request->order_status == 'cancel' ? OrderStatus::CANCELLED->value : OrderStatus::CONFIRM->value;

        $order->update([
            'order_status' => $orderStatus,
        ]);

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

        $order->refresh();

        // OrderMailEvent::dispatch($order);

        return $this->json('Order status updated successfully!', [
            'order' => SellerOrderResource::make($order),
        ]);
    }
}
