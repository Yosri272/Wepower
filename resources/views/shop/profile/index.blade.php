@extends('layouts.app')

@section('content')
    <div>
        <h4>
            {{ __('Profile Details') }}
        </h4>
    </div>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-3">
                <div class="rounded overflow-hidden">
                    <img src="{{ $shop->logo }}" alt="" width="140">
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex gap-2 justify-content-between ">
                        <h3 class="mb-2 pb-1">{{ $shop->name }}</h3>

                        <div>
                            <a href="{{ route('shop.profile.edit') }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-pen me-1"></i> {{ __('Edit') }}
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-3 align-items-center">
                        <div>
                            <i class="fa-solid fa-star text-warning"></i>
                            {{ $shop->averageRating }}
                        </div>

                        <div class="border" style="width: 0; height: 16px;"></div>

                        <div>
                            {{ $shop->reviews->count() }}
                            {{ __('Reviews') }}
                        </div>
                    </div>

                    <a href="/shops/{{ $shop->id }}" target="_blank" class="btn btn-outline-primary mt-3">
                        {{ __('View Live') }}
                    </a>
                </div>
            </div>

            <div class="border-top my-3"></div>

            <div class="d-flex gap-4 flex-wrap">
                <div class="d-flex flex-column border gap-2 p-3">
                    <div>
                        <span> {{ __('Total products') }}:</span> {{ $shop->products->count() }}
                    </div>
                    <div>
                        <span>{{ __('Total Orders') }}:</span> {{ $shop->orders->count() }}
                    </div>
                </div>

                <div>
                    <h5> {{ __('Shop Information') }}</h5>
                    <table class="table mb-0">
                        <tr>
                            <td class="border-top">{{ __('Name') }}</td>
                            <td class="border-top">{{ $shop->name }}</td>
                        </tr>

                        <tr>
                            <td>{{ __('Estimated Delivery Time') }}</td>
                            <td>{{ $shop->estimated_delivery_time }}</td>
                        </tr>
                    </table>
                </div>

                <div class="ms-lg-4">
                    <h5> {{ __('User Information') }}</h5>
                    <table class="table mb-0">
                        <tr>
                            <td class="border-top">{{ __('Name') }}</td>
                            <td class="border-top">{{ $shop->user?->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Phone number') }}</td>
                            <td>{{ $shop->user?->phone }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Email') }}</td>
                            <td>{{ $shop->user?->email }}</td>
                        </tr>
                    </table>
                </div>

            </div>

        </div>
    </div>
@endsection
