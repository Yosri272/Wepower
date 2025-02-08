@extends('layouts.app')

@section('content')
    <div>
        <h4>{{ __('Order Details') }}</h4>
    </div>

    <div class="row my-3">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <div class="d-flex flex-column gap-2">
                            <div class="fw-bold">
                                {{__('Order Id')}}: #{{ $order->prefix . $order->order_code }}
                            </div>
                            <div>
                                {{ $order->created_at->format('d M, Y h:i A') }}
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('shop.download-invoice', $order->id) }}" class="btn btn-primary">
                                <i class="fa-solid fa-receipt"></i>
                                {{__('Print Invoice')}}
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-end justify-content-end w-100 gap-2 mt-3">

                        <div class="d-flex gap-2">
                            <span>{{__('Order Status')}}: </span>
                            <span class="badge rounded-pill text-bg-primary">
                                {{ $order->order_status }}
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <span>{{__('Payment Status')}}: </span>
                            <span class="fw-bold">{{ $order->payment_status }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <span>{{__('Payment Method')}}: </span>
                            <span class="fw-bold">{{ $order->payment_method }}</span>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    @if ($businessModel == 'multi')
                                        <th>{{ __('Shop') }}</th>
                                    @endif
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <img src="{{ $product->thumbnail }}" alt="" width="40"
                                                height="40" loading="lazy">
                                            <span>{{ $product->name }}</span>
                                        </td>
                                        @if ($businessModel == 'multi')
                                            <td>{{ $product->shop->name }}</td>
                                        @endif
                                        <td>{{ $product->pivot->quantity }}</td>
                                        <td>
                                            {{ showCurrency($product->discount_price ?? $product->price) }}
                                        </td>
                                        <td>
                                            {{ showCurrency($product->pivot->quantity * ($product->discount_price ?? $product->price)) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-column align-items-end justify-content-end mt-2">
                        <div class="d-flex gap-lg-5 gap-3">

                            <div class="d-flex flex-column gap-2 align-items-end">
                                <div>{{ __('Total Price') }}:</div>
                                <div>{{ __('Coupon Discount') }}:</div>
                                <div>{{ __('Delivery Charge') }}:</div>
                                <div class="fw-bold">{{ __('Grand Total') }}:</div>
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-end">
                                <div>{{ showCurrency($order->total_amount) }}</div>
                                <div>{{ showCurrency($order->coupon_discount) }}</div>
                                <div>{{ showCurrency($order->delivery_charge) }}</div>
                                <div class="fw-bold">{{ showCurrency($order->payable_amount) }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!--##### Order & Shipping Info #####-->
            <div class="card">
                <div class="card-body">
                    <h5>{{__('Order & Shipping Info')}}</h5>

                    <div class="mt-3">
                        <div>{{__('Change Order Status')}}</div>
                        <div class="dropdown w-100 mt-1">
                            <a class="btn border  text-start dropdown-toggle w-100" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $order->order_status->value }}
                            </a>

                            <ul class="dropdown-menu w-100">
                                @foreach ($orderStatus as $satus)
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            {{ $satus->value }}
                                        </a>
                                        {{-- <a class="dropdown-item"
                                            href="{{ route('shop.order.status.change', $order->id) }}?status={{ $satus->value }}">
                                            {{ $satus->value }}
                                        </a> --}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="border rounded d-flex justify-content-between p-2 mt-3">
                        <div>{{__('Payment Status')}}</div>
                        <div class="d-flex align-items-center gap-1">
                            <span>{{ $order->payment_status }}</span>
                            <label class="switch mb-0">
                                <a href="#">
                                    <input type="checkbox" {{ $order->payment_status->value == 'Paid' ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        </div>
                    </div>

                    @if ($order->order_status->value != 'Pending')
                        <div class="border rounded d-flex justify-content-between align-items-center p-2 mt-3">
                            <div class="fw-medium">{{__('Assign Rider')}}</div>
                            <div class="d-flex align-items-center gap-1">

                                @if ($order->driverOrder)
                                    <span>{{ $order->driverOrder->driver?->user?->fullName }}</span>
                                @else
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#assignRider">
                                        <i class="bi bi-bicycle"></i>
                                        {{__('Assign')}}
                                    </button>
                                @endif

                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!--##### Shipping Address #####-->
            <div class="card mt-3">
                <div class="card-body">
                    <h5>{{__('Shipping Address')}}</h5>

                    <div class="mt-3 d-flex flex-column gap-2">
                        <div>
                            <span>{{__('Name')}}: </span>
                            <span class="fw-medium">{{ $order->address?->name }}</span>
                        </div>
                        <div>
                            <span>{{__('Phone')}}: </span>
                            <span class="fw-medium">{{ $order->address?->phone }}</span>
                        </div>
                        <div>
                            <span>{{__('Address Type')}}: </span>
                            <span class="fw-medium">{{ $order->address?->address_type }}</span>
                        </div>
                        <div>
                            <span>{{__('Area')}}: </span>
                            <span class="fw-medium">{{ $order->address?->area }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <div>
                                <span>{{__('Road No')}}: </span>
                                <span class="fw-medium">{{ $order->address?->road_no }}</span>,
                            </div>
                            <div>
                                <span>{{__('Flat No')}}: </span>
                                <span class="fw-medium">{{ $order->address?->flat_no }}</span>,
                            </div>
                            <div>
                                <span>{{__('House No')}}: </span>
                                <span class="fw-medium">{{ $order->address?->house_no }}</span>
                            </div>
                        </div>
                        <div>
                            <span>{{__('Post Code')}}: </span>
                            <span class="fw-medium">{{ $order->address?->post_code }}</span>
                        </div>
                        <div>
                            <span>{{__('Address Line')}}: </span>
                            <span class="fw-medium">{{ $order->address?->address_line }}</span>
                        </div>
                        <div>
                            <span>{{__('Address Line')}} 2: </span>
                            <span class="fw-medium">{{ $order->address?->address_line2 }}</span>
                        </div>
                    </div>

                </div>
            </div>

            <!--##### Shipping Address #####-->
            <div class="card mt-3">
                <div class="card-body">
                    <h5>{{__('Customer Info')}}</h5>

                    <div class="mt-3 d-flex flex-column gap-2">
                        <div>
                            <span>{{__('Name')}}: </span>
                            <span class="fw-medium">{{ $order->customer?->user?->name }}</span>
                        </div>
                        <div>
                            <span>{{__('Phone')}}: </span>
                            <span class="fw-medium">{{ $order->customer?->user?->phone }}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Assign Rider Modal -->
    <form action="{{ route('admin.rider.assign.order', $order->id) }}" method="POST">
        @csrf
        <div class="modal fade" id="assignRider">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title fs-5">{{__('Select a rider')}}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-2 flex-column">
                            @foreach ($riders as $rider)
                                <div class="w-100">
                                    <input type="radio" name="rider" value="{{ $rider->id }}"
                                        id="rider{{ $rider->id }}" class="btn-check">
                                    <label for="rider{{ $rider->id }}" class="btn riderSelectBtn">
                                        <div>
                                            <img src="{{ $rider->user->thumbnail }}" alt="profile" class="profilePhoto" />
                                            <span class="riderName">
                                                {{ $rider->user->fullName }}
                                            </span>
                                        </div>
                                        <div class="d-flex gap-1 align-items-center">
                                            <span class="text-muted inComplated">
                                                {{__('Incomplete Orders')}}:
                                            </span>
                                            <span class="totalOrders">{{ $rider->incompleteOrders()->count() }}</span>
                                        </div>

                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            {{__('Assign Now')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
<style>
    .min-w-200 {
        min-width: 200px;
        display: inline;
    }
</style>
