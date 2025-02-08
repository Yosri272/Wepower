@extends('layouts.app')

@section('content')
    <div>
        <h4>
            {{ __('Order Details') }}
        </h4>
    </div>

    <div class="row my-3">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <div class="d-flex flex-column gap-2">
                            <div class="fw-bold d-flex gap-2 align-items-center">
                                <span>
                                    {{ __('Order Id') }}: #{{ $order->prefix . $order->order_code }}
                                </span>
                                @if ($order->orderGift)
                                    <img src="{{ $order->orderGift->gift->thumbnail }}" alt="" width="32"
                                        height="32" class="rounded">
                                @endif
                            </div>
                            <div>
                                {{ $order->created_at->format('d M, Y h:i A') }}
                            </div>
                        </div>
                        <div>
                            <a href="{{ $order->pos_order ? route('shop.pos.invoice', $order->id) : route('shop.download-invoice', $order->id) }}" class="btn btn-primary" target="{{ $order->pos_order ? '_blank' : '' }}">
                                <i class="fa-solid fa-receipt"></i>
                                {{ __('Print Invoice') }}
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-end justify-content-end w-100 gap-2 mt-3">
                        <div class="d-flex gap-2">
                            <span>{{ __('Order Status') }}: </span>
                            <span class="badge rounded-pill text-bg-primary">
                                {{ __($order->order_status->value) }}
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <span>{{ __('Payment Status') }}: </span>
                            <span class="fw-bold">{{ __($order->payment_status->value) }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <span>{{ __('Payment Method') }}: </span>
                            <span class="fw-bold">{{ __($order->payment_method->value) }}</span>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th class="text-center">{{ __('Quantity') }}</th>
                                    <th class="text-center">{{ __('Price') }}</th>
                                    <th class="text-center">{{ __('Total') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $key => $product)
                                    <tr class="{{ $product->pivot->is_gift ? 'table-warning' : '' }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <img src="{{ $product->thumbnail }}" alt="" width="40"
                                                    height="40" loading="lazy">
                                                <span>{{ ucfirst($product->name) }}</span>

                                                @if ($product->pivot->is_gift)
                                                    <button
                                                        class="btn btn-primary btn-sm overflow-hidden d-flex align-items-center"
                                                        data-bs-toggle="modal" data-bs-target="#giftModal">
                                                        <img src="{{ $order->orderGift->gift->thumbnail }}" width="26"
                                                            height="26" class="rounded">
                                                        <span class="truncate"
                                                            style="max-width: 55px">{{ $order->orderGift->gift->name }}</span>
                                                    </button>
                                                @endif

                                            </div>
                                        </td>
                                        <td class="text-center">{{ $product->pivot->quantity }}</td>
                                        <td class="text-center">
                                            {{ showCurrency($product->discount_price ?? $product->price) }}
                                        </td>
                                        <td class="text-center">
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
                    <h5>{{ __('Order & Shipping Info') }}</h5>

                    <div class="mt-3">
                        <div>{{ __('Change Order Status') }}</div>
                        <div class="dropdown w-100 mt-1">
                            <a class="btn border  text-start dropdown-toggle w-100" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __($order->order_status->value) }}
                            </a>

                            <ul class="dropdown-menu w-100">
                                @foreach ($orderStatus as $satus)
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('shop.order.status.change', $order->id) }}?status={{ $satus->value }}">{{ __($satus->value) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="border rounded d-flex justify-content-between p-2 mt-3">
                        <div>
                            <span>{{ __('Payment Status') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span>{{ __($order->payment_status->value) }}</span>
                            <label class="switch mb-0">
                                <a href="{{ route('shop.order.payment.status.toggle', $order->id) }}">
                                    <input type="checkbox" {{ $order->payment_status->value == 'Paid' ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!--##### Shipping Address #####-->
            <div class="card mt-3">
                <div class="card-body">
                    <h5>{{ __('Shipping Address') }}</h5>

                    <div class="mt-3 d-flex flex-column gap-2">
                        <div>
                            <span>{{ __('Name') }}: </span>
                            <span class="fw-medium">{{ $order->address?->name }}</span>
                        </div>
                        <div>
                            <span>{{ __('Phone') }}: </span>
                            <span class="fw-medium">{{ $order->address?->phone }}</span>
                        </div>
                        <div>
                            <span>{{ __('Address Type') }}: </span>
                            <span class="fw-medium">{{ $order->address?->address_type }}</span>
                        </div>
                        <div>
                            <span>{{ __('Area') }}: </span>
                            <span class="fw-medium">{{ $order->address?->area }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <div>
                                <span>{{ __('Road No') }}: </span>
                                <span class="fw-medium">{{ $order->address?->road_no }}</span>,
                            </div>
                            <div>
                                <span>{{ __('Flat No') }}: </span>
                                <span class="fw-medium">{{ $order->address?->flat_no }}</span>,
                            </div>
                            <div>
                                <span>{{ __('House No') }}: </span>
                                <span class="fw-medium">{{ $order->address?->house_no }}</span>
                            </div>
                        </div>
                        <div>
                            <span>{{ __('Post Code') }}: </span>
                            <span class="fw-medium">{{ $order->address?->post_code }}</span>
                        </div>
                        <div>
                            <span>{{ __('Address Line') }}: </span>
                            <span class="fw-medium">{{ $order->address?->address_line }}</span>
                        </div>
                        <div>
                            <span>{{ __('Address Line') }} 2: </span>
                            <span class="fw-medium">{{ $order->address?->address_line2 }}</span>
                        </div>
                    </div>

                </div>
            </div>

            <!--##### Shipping Address #####-->
            <div class="card mt-3">
                <div class="card-body">
                    <h5>{{ __('Customer Info') }}</h5>

                    <div class="mt-3 d-flex flex-column gap-2">
                        <div>
                            <span>{{ __('Name') }}: </span>
                            <span class="fw-medium">{{ $order->customer?->user?->name }}</span>
                        </div>
                        <div>
                            <span>{{ __('Phone') }}: </span>
                            <span class="fw-medium">{{ $order->customer?->user?->phone }}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Gift Modal -->
    <div class="modal fade" id="giftModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="giftModalLabel">
                        {{ __('Gift Details') }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($order->orderGift)
                        <!--##### Gift Information #####-->
                        <div class="card card-body">
                            <div class="d-flex gap-3">
                                <div class="">
                                    <img src="{{ $order->orderGift->gift->thumbnail }}" alt="" width="100">
                                </div>
                                <div class="flex-grow-1">

                                    <table class="table">
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Price') }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $order->orderGift->gift->name }}</td>
                                            <td>
                                                {{ showCurrency($order->orderGift->gift->price) }}
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <!--##### Others Information #####-->
                        <div class="mt-3 card">
                            <div class="card-body">
                                <h5>{{ __('Others Information') }}</h5>
                                <div class="d-flex gap-3 flex-wrap">

                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold m-0 mt-3"> {{ __('Sender Name') }} </h5>
                                        <p>{{ $order->orderGift->sender_name }}</p>
                                    </div>

                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold m-0 mt-3"> {{ __('Receiver Name') }} </h5>
                                        <p>{{ $order->orderGift->receiver_name }}</p>
                                    </div>

                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold m-0 mt-3"> {{ __('Notes') }} </h5>
                                        <p>{{ $order->orderGift->note }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--##### Shipping Address #####-->
                        @if ($order->orderGift->address)
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5>{{ __('Shipping Address') }}</h5>

                                    <div class="mt-3 d-flex flex-column gap-2">
                                        <div>
                                            <span>{{ __('Name') }}: </span>
                                            <span class="fw-medium">{{ $order->orderGift->address?->name }}</span>
                                        </div>
                                        <div>
                                            <span>{{ __('Phone') }}: </span>
                                            <span class="fw-medium">{{ $order->orderGift->address?->phone }}</span>
                                        </div>
                                        <div>
                                            <span>{{ __('Address Type') }}: </span>
                                            <span class="fw-medium">{{ $order->orderGift->address?->address_type }}</span>
                                        </div>
                                        <div>
                                            <span>{{ __('Area') }}: </span>
                                            <span class="fw-medium">{{ $order->orderGift->address?->area }}</span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <div>
                                                <span>{{ __('Road No') }}: </span>
                                                <span class="fw-medium">{{ $order->orderGift->address?->road_no }}</span>,
                                            </div>
                                            <div>
                                                <span>{{ __('Flat No') }}: </span>
                                                <span class="fw-medium">{{ $order->orderGift->address?->flat_no }}</span>,
                                            </div>
                                            <div>
                                                <span>{{ __('House No') }}: </span>
                                                <span class="fw-medium">{{ $order->orderGift->address?->house_no }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span>{{ __('Post Code') }}: </span>
                                            <span class="fw-medium">{{ $order->orderGift->address?->post_code }}</span>
                                        </div>
                                        <div>
                                            <span>{{ __('Address Line') }}: </span>
                                            <span
                                                class="fw-medium">{{ $order->orderGift->address?->address_line }}</span>
                                        </div>
                                        <div>
                                            <span>{{ __('Address Line') }} 2: </span>
                                            <span
                                                class="fw-medium">{{ $order->orderGift->address?->address_line2 }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .min-w-200 {
            min-width: 200px;
            display: inline;
        }

        .table-warning {
            --bs-table-bg: #f1f5f9 !important;
        }
    </style>
@endsection
