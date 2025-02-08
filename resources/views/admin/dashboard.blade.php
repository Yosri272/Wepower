@extends('layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div> {{ __('Dashboard') }}
                    <div class="page-title-subheading">
                        {{ __('This is a admin dashboard') }}.
                    </div>
                </div>
            </div>
        </div>

        @if (app()->environment('local'))
            <div class="alert alert-danger d-flex align-items-center gap-1 justify-content-between mt-2 mb-0" role="alert"
                id="alertBox">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-bell"></i>
                    <div>
                        <strong>{{ __('Note') }}</strong> {{ __('Every 3 hours all data will be cleared') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    </div>

    @php
        $text = 'Total ' . ($businessModel == 'single' ? 'Categories' : 'Shops');
    @endphp

    <div class="row">
        <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
            <div class="dashboard-summery bg-shop">
                <h2>{{ $businessModel == 'single' ? $totalCategories : $totalShop }}</h2>
                <h3>{{ __($text) }}</h3>
                <div class="icon">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
            <div class="dashboard-summery bg-midnight-bloom">
                <h2>{{ $totalProduct }}</h2>
                <h3>{{ __('Total Products') }}</h3>
                <div class="icon">
                    <i class="bi bi-basket"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
            <div class="dashboard-summery bg-alternate">
                <h2>{{ $totalOrder }}</h2>
                <h3>{{ __('Total Orders') }}</h3>
                <div class="icon">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
            <div class="dashboard-summery bg-grow-early">
                <h2>{{ $totalCustomer }}</h2>
                <h3>{{ __('Total Customers') }}</h3>
                <div class="icon">
                    <i class="bi bi-person-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!---- Order Analytics -->
    <div class="card">
        <div class="card-body">
            <div class="cardTitleBox">
                <h5 class="card-title chartTitle">
                    <i class="bi bi-bar-chart"></i> {{ __('Order Analytics') }}
                </h5>
            </div>

            @php
                $icons = [
                    'pending' => 'bi-clock',
                    'confirm' => 'bi-bag-check-fill',
                    'processing' => 'bi-arrow-repeat',
                    'pickup' => 'bi-bicycle',
                    'delivered' => 'bi-patch-check-fill',
                    'onTheWay' => 'bi-bicycle',
                    'cancelled' => 'bi-x-circle',
                ];
            @endphp

            <div class="d-flex flex-wrap gap-3 orderStatus">
                @foreach ($orderStatuses as $status)
                    <a href="{{ route('admin.order.index', str_replace(' ', '_', $status->value)) }}"
                        class="d-flex align-items-center gap-3 status flex-grow-1">
                        <div>
                            <i class="bi {{ $icons[Str::camel($status->value)] }}"></i>
                            <span>{{ __($status->value) }}</span>
                        </div>
                        <span class="count">{{ ${Str::camel($status->value)} }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!---- Shop Wallet -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="cardTitleBox">
                <h5 class="card-title chartTitle">
                    <i class="bi bi-wallet2"></i> {{ __('Admin Wallet') }}
                </h5>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <div class="wallet py-4 px-3">
                        <div class="wallet-icon">
                            <img src="{{ asset('assets/images/wallet.png') }}" alt="" width="100%">
                        </div>
                        <h3 class="balance">{{ showCurrency(auth()->user()->wallet->balance) }}</h3>

                        <div class="title">{{ __('Total Earning') }}</div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="row gy-4">

                        <div class="col-md-6">
                            <div class="wallet-others py-md-4">
                                <div>
                                    <div class="amount">{{ showCurrency($alreadyWithdraw) }}</div>
                                    <div class="title">{{ __('Already Withdraw') }}</div>
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
                                    <div class="title">{{ __('Pending Withdraw') }}</div>
                                </div>
                                <div class="icon">
                                    <img src="{{ asset('assets/icons/pendingWithdraw.png') }}" alt="icon" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wallet-others py-md-4">
                                <div>
                                    <div class="amount">{{ showCurrency($totalCommission) }}</div>
                                    <div class="title">{{ __('Total Commission') }}</div>
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
                                    <div class="title">{{ __('Rejected Withdraw') }}</div>
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

    <!-- User Overview -->
    <div class="row">
        <div class="col-xl-8 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">{{ __('Orders Summary') }}</h5>
                        <p class="lastAll"><i class="bi bi-calendar-date pe-1"></i>
                            {{ __('latest 6th orders') }}
                        </p>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><strong>{{ __('Order ID') }}</strong></th>
                                    <th><strong>{{ __('Qty') }}</strong></th>
                                    <th><strong>{{ __('Shop') }}</strong></th>
                                    <th><strong>{{ __('Date') }}</strong></th>
                                    <th><strong>{{ __('Status') }}</strong></th>
                                    <th><strong>{{ __('Action') }}</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latestOrders as $order)
                                    <tr>
                                        <td class="tableId">#{{ $order->prefix . $order->order_code }}</td>
                                        <td class="tableId">
                                            {{ $order->products->count() }}
                                        </td>
                                        <td class="tableCustomar">
                                            {{ $order->shop->name }}
                                        </td>
                                        <td class="tableId">
                                            {{ $order->created_at->format('d M, Y') }}
                                        </td>
                                        @php
                                            $status = Str::ucfirst(str_replace(' ', '', $order->order_status->value));
                                        @endphp
                                        <td class="tableStatus">
                                            <div class="statusItem">
                                                <div class="circleDot animated{{ $status }}"></div>
                                                <div class="statusText">
                                                    <span class="status{{ $status }}">
                                                        {{ $order->order_status->value }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="tableAction">
                                            <a href="{{ route('admin.order.show', $order->id) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Order details"
                                                class="circleIcon btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('shop.download-invoice', $order->id) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Download Invoice"
                                                class="circleIcon btn-outline-success btn btn-sm">
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
        </div>
        <div class="col-xl-4 mt-3">
            <div class="card h-100">
                <div class="card-header py-3">
                    <h5 class="card-title m-0">
                        <i class="bi bi-person fz-16"></i> {{ __('User Overview') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div id="donut"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Top Customer -->
        <div class="col-xxl-4 col-lg-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">
                            <i class="bi bi-people fz-18"></i> {{ __('Top Customer') }}
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        @foreach ($topCustomers as $customer)
                            <div class="customar-section">
                                <div class="customat-details">
                                    <div class="customar-image">
                                        <img src="{{ $customer->user->thumbnail }}" alt="">
                                    </div>
                                    <div class="customar-about">
                                        <p class="name">{{ Str::limit($customer->user->name, 30, '...') }}</p>
                                        <p class="order">
                                            {{ __('Orders') }}:
                                            <span class="">{{ $customer->orders_count }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="customar-socal-icon">
                                    <a href="#" class="circleIcon btn btn-outline-dark">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    <a href="#" class="circleIcon btn btn-outline-dark">
                                        <i class="bi bi-bookmark-check-fill"></i>
                                    </a>

                                    <a href="#" class="circleIcon btn btn-outline-dark">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </a>

                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        @if ($businessModel == 'multi')
            <!-- Top Shops -->
            <div class="col-xxl-4 col-lg-6 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="cardTitleBox">
                            <h5 class="card-title chartTitle">
                                <i class="bi bi-shop fz-16"></i> {{ __('Top Trending Shops') }}
                            </h5>
                        </div>

                        <div class="d-flex flex-column gap-1">
                            @foreach ($topShops as $shop)
                                <a href="{{ route('admin.shop.show', $shop->id) }}" class="customar-section">
                                    <div class="customat-details">
                                        <div class="customar-image">
                                            <img src="{{ $shop->logo }}" alt="">
                                        </div>
                                        <div class="customar-about">
                                            <p class="name text-dark">
                                                {{ Str::limit($shop->name, 30, '...') }}
                                            </p>
                                            <p class="order">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <i class="bi bi-star-half text-warning"></i>
                                                {{ $shop->average_rating }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="border rounded text-primary px-2 py-1 flex-shrink-0"
                                        style="font-size: 13px">
                                        <div>{{ __('Orders') }}: {{ $shop->orders_count }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Top Selling Products -->
        <div class="col-xxl-4 col-lg-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">
                            <i class="bi bi-bag-check fz-16"></i> {{ __('Top Selling Products') }}
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        @foreach ($topSellingProducts as $product)
                            <a href="{{ route('admin.product.show', $product->id) }}" class="customar-section">
                                <div class="customat-details">
                                    <div class="customar-image">
                                        <img src="{{ $product->thumbnail }}" alt="">
                                    </div>
                                    <div class="customar-about">
                                        <p class="text-dark name">
                                            {{ Str::limit($product->name, 30, '...') }}
                                        </p>
                                        <p class="order">{{__('Rating')}}: {{ number_format($product->reviews->avg('rating'), 1) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="border rounded text-primary px-2 py-1 flex-shrink-0" style="font-size: 13px">
                                    <div>{{ __('Sold') }}: {{ $product->orders_count }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Rating Products -->
        <div class="col-xxl-4 col-lg-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">
                            <i class="bi bi-stars fz-16"></i> {{ __('Top Rating Products') }}
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        @foreach ($topReviewProducts as $product)
                            <a href="{{ route('admin.product.show', $product->id) }}" class="customar-section">
                                <div class="customat-details">
                                    <div class="customar-image">
                                        <img src="{{ $product->thumbnail }}" alt="">
                                    </div>
                                    <div class="customar-about">
                                        <p class="name text-dark">{{ Str::limit($product->name, 30, '...') }}</p>
                                        <p class="order">{{__('Sold')}}: {{ $product->orders->count() }}</p>
                                    </div>
                                </div>
                                <div class="border rounded text-primary px-2 py-1 flex-shrink-0" style="font-size: 13px">
                                    <div>{{ __('Rating') }}: <i class="bi bi-star-fill text-warning"></i>
                                        {{ number_format($product->average_rating, 1) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Favorite Products -->
        <div class="col-xxl-4 col-lg-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">
                            <i class="bi bi-bag-heart fz-16"></i> {{ __('Most Favorite Products') }}
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        @foreach ($topFavorites as $product)
                            <a href="{{ route('admin.product.show', $product->id) }}" class="customar-section">
                                <div class="customat-details">
                                    <div class="customar-image">
                                        <img src="{{ $product->thumbnail }}" alt="">
                                    </div>
                                    <div class="customar-about">
                                        <p class="name text-dark">{{ Str::limit($product->name, 30, '...') }}</p>
                                        <div class="d-flex gap-2 align-items-center">
                                            <p class="order">Sold: {{ $product->orders->count() }}</p>
                                            <div class="border-start" style="width: 1px; height: 14px;"></div>
                                            <p class="order">
                                                {{ __('Rating') }}: <i class="bi bi-star-fill text-warning"></i>
                                                {{ number_format($product->average_rating, 1) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border rounded text-danger px-2 py-1 flex-shrink-0" style="font-size: 16px">
                                    <div>{{ $product->favorites_count }} <i class="bi bi-heart-fill text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>


    <style>
        .apexcharts-legend-series {
            margin: 6px 5px !important;
        }
    </style>
@endsection

@push('scripts')
    <script>
        const hideAlert = () => {
            setTimeout(() => {
                document.getElementById('alertBox').classList.add('d-none');
            }, 5000);
        }

        hideAlert();

        var optionDonut = {
            chart: {
                type: 'donut',
                width: '100%',
                height: 320
            },
            dataLabels: {
                enabled: false,
            },
            plotOptions: {
                pie: {
                    customScale: 0.8,
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '18px',
                                color: undefined,
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                color: undefined,
                                formatter: function(val) {
                                    return val
                                }
                            }
                        }
                    },
                    offsetY: 20,
                },
                stroke: {
                    colors: undefined
                }
            },
            colors: ['#00D8B6', '#6d28d9', '#0d9488'],
            series: [{{ $totalCustomer }}, {{ $totalShop }}, 0],
            labels: ["{{ __('Customer') }}", "{{ __('Shop') }}", "{{ __('Rider') }}"],
            legend: {
                position: 'left',
                fontSize: '16px',
                offsetY: 100
            }
        }

        var donut = new ApexCharts(
            document.querySelector("#donut"),
            optionDonut
        )
        donut.render();
    </script>
@endpush
