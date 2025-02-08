@extends('layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    {{ __('POS Sales') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="cardTitleBox">
                <h5 class="card-title chartTitle">
                    {{ __('Orders Summary') }}
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-responsive-lg">
                    <thead>
                        <tr>
                            <th>{{ __('Order ID') }}</th>
                            <th>{{ __('Order Date') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Payment Method') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    {{ $order->prefix . $order->order_code }}
                                    @if ($order->orderGift)
                                        <span class="badge rounded-pill bg-danger d-inline-flex align-items-center justify-content-center gap-1">
                                            <i class="fa-solid fa-gift"></i>
                                            <span>{{ __('Gift') }}</span>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $order->customer?->user?->name }}</td>
                                <td>
                                    {{ showCurrency($order->payable_amount) }}
                                    <br>
                                    <span class="badge rounded-pill text-bg-primary">
                                        {{ __($order->payment_status->value) }}
                                    </span>
                                </td>
                                <td>{{ __($order->payment_method->value) }}</td>
                                <td>{{ __($order->order_status->value) }}</td>
                                <td>
                                    <a href="{{ route('shop.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="{{__('view order details')}}"
                                        class="circleIcon btn-outline-primary">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('shop.pos.invoice', $order->id) }}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{__('Download Invoice')}}" class="circleIcon btn-outline-success btn">
                                        <i class="bi bi-arrow-down-circle"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

    <div class="my-3">
        {{ $orders->links() }}
    </div>
@endsection
