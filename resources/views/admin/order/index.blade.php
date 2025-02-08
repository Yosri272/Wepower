@extends('layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    {{ __('Orders') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table border table-responsive-lg">
                    <thead>
                        <tr>
                            <th style="min-width: 85px">{{ __('Order ID') }}</th>
                            <th>{{ __('Order Date') }}</th>
                            <th>{{ __('Customer') }}</th>
                            @if ($businessModel == 'multi')
                            <th>{{__('Shop') }}</th>
                            @endif
                            <th>{{__('Total Amount') }}</th>
                            <th>{{__('Payment Method') }}</th>
                            <th>{{__('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="w-auto">{{ $order->prefix . $order->order_code }}</td>
                                <td class="w-min">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td class="w-min">{{ $order->customer?->user?->name }}</td>

                                @if ($businessModel == 'multi')
                                <td class="w-min">
                                    {{ $order->shop?->name }}
                                </td>
                                @endif
                                <td class="w-min">
                                    {{ showCurrency($order->payable_amount) }}
                                    <br>
                                    <span class="badge rounded-pill text-bg-primary">{{ $order->payment_status }}</span>
                                </td>
                                <td class="w-min">{{ $order->payment_method }}</td>
                                <td class="w-min">
                                    <a href="{{ route('admin.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="{{__('view details')}}"
                                        class="circleIcon btn-outline-primary">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('shop.download-invoice', $order->id) }}" data-bs-toggle="tooltip" data-bs-placement="left"
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
