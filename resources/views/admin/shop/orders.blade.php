@extends('layouts.app')

@section('content')
    <div>
        <h4>Shop Details</h4>
    </div>

     @include('admin.shop.header-nav')

    <div class="container-fluid mt-3">

        <div class="card">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Order Date') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Total Amount') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->prefix . $order->order_code }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->customer?->user?->name }}</td>
                                    <td>
                                        {{ showCurrency($order->payable_amount) }}
                                        <br>
                                        <span class="badge rounded-pill text-bg-primary">{{ $order->payment_status }}</span>
                                    </td>
                                    <td>{{ $order->payment_method }}</td>
                                    <td>
                                        <a href="{{ route('admin.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-title="view order details"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fa-solid fa-eye"></i>
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

    </div>
@endsection
