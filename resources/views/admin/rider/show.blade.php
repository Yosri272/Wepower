@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Rider Details') }}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="row">
            <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                <div class="dashboard-summery bg-alternate">
                    <h2>{{ $totalPending }}</h2>
                    <h3>
                        {{ __('ToDo Orders') }}
                    </h3>
                    <div class="icon">
                        <i class="bi bi-bicycle"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                <div class="dashboard-summery bg-midnight-bloom">
                    <h2>{{ $totalDelivery }}</h2>
                    <h3>
                        {{ __('Total Delivered') }}
                    </h3>
                    <div class="icon">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                <div class="dashboard-summery bg-grow-early">
                    <h2>{{ showCurrency($driver->total_cash_collected) }}</h2>
                    <h3>
                        {{ __('Current Cash Collected') }}
                    </h3>
                    <div class="icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                <div class="dashboard-summery bg-arielle-smile">
                    <h2>{{ showCurrency($allCashCollected) }}</h2>
                    <h3>
                        {{ __('Total Cash Collected') }}
                    </h3>
                    <div class="icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card my-3">
            <div class="card-body">
                <div class="cardTitleBox">
                    <h5 class="card-title chartTitle">
                        <i class="bi bi-wallet2"></i> {{__('Rider Wallet')}}
                    </h5>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="wallet py-4 px-3">
                            <div class="wallet-icon">
                                <img src="{{ asset('assets/images/wallet.png') }}" alt="" width="100%">
                            </div>
                            <h3 class="balance">{{ showCurrency($wallet->balance) }}</h3>

                            <div class="title">
                                {{ __('Current Balance') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="row gy-4">

                            <div class="col-md-6">
                                <div class="wallet-others py-md-4">
                                    <div>
                                        <div class="amount">{{ showCurrency($alreadyWithdraw) }}</div>
                                        <div class="title">
                                            {{ __('Already Withdraw') }}
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons/alreadyWithdraw.png') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wallet-others py-md-4">
                                    <div>
                                        <div class="amount">{{ showCurrency($pendingWithdraw) }}</div>
                                        <div class="title">
                                            {{ __('Pending Withdraw') }}
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons/pendingWithdraw.png') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wallet-others py-md-4">
                                    <div>
                                        <div class="amount">{{ showCurrency($driver->total_cash_collected) }}</div>
                                        <div class="title">
                                            {{ __('Current Cash Collected') }}
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons/totalEarn.png') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wallet-others py-md-4">
                                    <div>
                                        <div class="amount">{{ showCurrency($deniedWithddraw) }}</div>
                                        <div class="title">
                                        {{__('Rejected Withdraw') }}
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons/reject.png') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="cardTitleBox">
                    <h5 class="card-title chartTitle">
                       {{__('All Assigned Orders')}}
                    </h5>
                </div>
                <div class="table-responsive">

                    <table class="table border table-responsive-lg">
                        <thead>
                            <tr>
                                <th style="min-width: 85px">{{ __('Order ID') }}</th>
                                <th>{{ __('Assigned At') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Shop') }}</th>
                                <th>{{ __('Total Amount') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Order Status') }}</th>
                                <th style="width: 80px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $orders = $driver->orders()->orderBy('pivot_created_at', 'desc')->orderBy('pivot_is_completed', 'asc')->paginate(20);
                            @endphp
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="w-auto">
                                        #{{ $order->prefix . $order->order_code }}
                                    </td>
                                    <td class="w-min">
                                        {{ $order->pivot->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="w-min">
                                        {{ $order->customer?->user?->name }}
                                    </td>

                                    <td class="w-min">
                                        {{ $order->shop?->name }}
                                    </td>
                                    <td class="w-min">
                                        {{ showCurrency($order->payable_amount) }}
                                        <br>
                                        <span class="badge rounded-pill text-bg-primary">{{ $order->payment_status }}</span>
                                    </td>
                                    <td class="w-min">{{ $order->payment_method }}</td>

                                    <td class="tableStatus">
                                        @php
                                            $status = Str::ucfirst(str_replace(' ', '', $order->order_status->value));
                                        @endphp
                                        <div class="statusItem">
                                            <div class="circleDot animated{{ $status }}"></div>
                                            <div class="statusText">
                                                <span class="status{{ $status }}">
                                                    {{ $order->order_status->value }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-title="{{__('view details')}}"
                                            class="circleIcon btn-outline-primary">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $orders->links() }}

                </div>
            </div>
        </div>

    </div>

    <!-- Withdraw Modal -->
    <form action="{{ route('admin.withdraw.update', $user->id) }}" method="POST">
        @csrf
        <div class="modal fade" id="withdrawModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            {{ __('Withdraw Update') }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <x-select name="status" label="Withdraw Status" required="true">
                                <option value="approved">{{ __('Approved') }}</option>
                                <option value="denied">{{ __('Denied') }}</option>
                            </x-select>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">{{ __('Any Reason') }}</label>
                            <textarea name="reason" placeholder="{{ __('Enter Any Reason') }}" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
