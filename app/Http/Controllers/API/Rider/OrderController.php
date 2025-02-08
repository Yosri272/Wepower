<?php

namespace App\Http\Controllers\API\Rider;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderIdRequest;
use App\Http\Requests\StatusUpdateRequest;
use App\Http\Resources\RiderOrderDetailsResource;
use App\Http\Resources\RiderOrderResource;
use App\Repositories\DriverOrderRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $driver = auth()->user()->driver;

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 15;
        $skip = ($page * $perPage) - $perPage;

        $driverOrders = $driver->driverOrders()->where('is_completed', false)->with('order')->orderBy('id', 'DESC');

        $total = $driverOrders->count();

        $toDoOrder = $driver->driverOrders()->where('is_completed', false)->count();
        $completedOrder = $driver->driverOrders()->where('is_completed', true)->count();

        return $this->json('dashboard order list', [
            'to_do_order' => $toDoOrder,
            'completed_order' => $completedOrder,
            'total' => $total,
            'orders' => RiderOrderResource::collection($driverOrders->skip($skip)->take($perPage)->get()),
        ]);
    }

    // show order details
    public function show(OrderIdRequest $request)
    {
        $order = OrderRepository::find($request->order_id);

        return $this->json('Order details', [
            'order' => RiderOrderDetailsResource::make($order),
        ]);
    }

    // status update
    public function statusUpdate(StatusUpdateRequest $request)
    {
        $driverOrder = DriverOrderRepository::query()->where('order_id', $request->order_id)->first();

        if (! $driverOrder) {
            return $this->json('Sorry, this order is not found', [], 422);
        } elseif ($driverOrder->is_completed) {
            return $this->json('Sorry, this order is already delivered', [], 422);
        }

        $order = $driverOrder->order;

        $orderStatus = null;

        switch ($order->order_status->value) {
            case OrderStatus::CONFIRM->value:
                $orderStatus = OrderStatus::PROCESSING->value;
                break;

            case OrderStatus::PROCESSING->value:
                $orderStatus = OrderStatus::PICKUP->value;
                break;

            case OrderStatus::PICKUP->value:
                $orderStatus = OrderStatus::ON_THE_WAY->value;
                break;

            case OrderStatus::ON_THE_WAY->value:
                $orderStatus = OrderStatus::DELIVERED->value;
                break;

            case OrderStatus::DELIVERED->value:
                $orderStatus = 'deliveredAndPaid';
                break;

            default:
                $orderStatus = OrderStatus::CONFIRM->value;
                break;
        }

        OrderRepository::OrderStatusUpdateFromRider($order, $driverOrder, $orderStatus);

        // OrderMailEvent::dispatch($order);

        return $this->json('Order status updated successfully!', [
            'order' => RiderOrderDetailsResource::make($driverOrder->order),
        ]);
    }

    public function statusWiseOrders(Request $request)
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

        $driverOrders = auth()->user()->driver->driverOrders()->when($search, function ($query) use ($search) {
            return $query->whereHas('order', function ($query) use ($search) {
                return $query->where('order_code', 'like', "%$search%")->orWhereHas('customer', function ($query) use ($search) {
                    $query->whereHas('user', function ($query) use ($search) {
                        return $query->where('name', 'like', "%$search%");
                    });
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
        })->when($orderStatus == 'to_deliver', function ($query) {
            return $query->where('is_completed', false);
        })->when($orderStatus == 'delivered', function ($query) {
            return $query->where('is_completed', true);
        });

        $total = $driverOrders->count();

        $driverOrders = $driverOrders->skip($skip)->take($perPage)->get();

        $totalOrders = auth()->user()->driver->driverOrders->count();
        $totalDelivered = auth()->user()->driver->driverOrders()->where('is_completed', true)->count();
        $totalToDeliver = auth()->user()->driver->driverOrders()->where('is_completed', false)->count();

        return $this->json('all order list', [
            'total' => $total,
            'all_orders' => $totalOrders,
            'to_deliver' => $totalToDeliver,
            'delivered' => $totalDelivered,
            'orders' => RiderOrderResource::collection($driverOrders),
        ]);
    }
}
