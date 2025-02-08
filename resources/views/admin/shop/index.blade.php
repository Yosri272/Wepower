@extends('layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="w-100 page-title-heading d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    {{__('Shops')}}
                    <div class="page-title-subheading">
                        {{__('This is a shops list')}}
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center gap-md-4">
                    <div class="d-flex gap-2 gap-md-3">
                        <button class="gridBtn" id="gridView" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="{{__('Grid View')}}">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button class="gridBtn" id="listView" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="{{__('List View')}}">
                            <i class="fa-solid fa-list-ul"></i>
                        </button>
                    </div>

                    <a href="{{ route('admin.shop.create') }}" class="btn py-2 btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        {{__('Create New Shop')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <div class="row row-gap mb-4 d-none" id="gridItem">
            @foreach ($shops as $key => $shop)
                @php
                    $roles = $shop->user?->getRoleNames()->toArray();
                    $isRoot = in_array('root', $roles);
                @endphp
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
                    <div class="card shadow-sm rounded-12 show-card position-relative overflow-hidden">
                        <div class="card-body shop p-2">
                            <div class="banner">
                                <img class="img-fit" src="{{ $shop->banner }}" />
                            </div>
                            <div class="main-content">
                                <div class="logo">
                                    <img class="img-fit" src="{{ $shop->logo }}" />
                                </div>
                                <div class="personal">
                                    <span class="name">{{ $shop->name }}</span>
                                    <span class="email">{{ $shop->user?->email }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2 px-3 mt-2">
                                <div class="item">
                                    <strong>{{ __('Status') }}</strong>
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{__('Click here to change status')}}">
                                        <a href="{{ $isRoot ? '#' : route('admin.shop.status.toggle', $shop->id) }}">
                                            <input type="checkbox" {{ $shop->user?->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </div>
                                <div class="item">
                                    <strong>{{ __('Products') }}</strong>
                                    <a href="{{ route('admin.shop.products', $shop->id) }}" class="btn btn-secondary btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{__('Click here to see products')}}">
                                        {{ $shop->products->count() }}
                                    </a>
                                </div>
                                <div class="item">
                                    <strong>{{ __('Orders') }}</strong>
                                    <a href="{{ route('admin.shop.orders', $shop->id) }}" class="btn btn-primary btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{__('Click here to see orders')}}">
                                        {{ $shop->orders->count() }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="overlay">
                            <a class="icons" href="{{ route('admin.shop.edit', $shop->id) }}" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="icons" href="{{ route('admin.shop.show', $shop->id) }}" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-4 d-none" id="listItem">
            <div class="table-responsive">

                <table class="table shopTable table-striped table-responsive-lg">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Logo') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Products') }}</th>
                            <th class="text-center">{{ __('Orders') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($shops as $key => $shop)
                            @php
                                $roles = $shop->user?->getRoleNames()->toArray();
                                $isRoot = in_array('root', $roles);
                            @endphp
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <div class="payment-image">
                                        <img class="img-fit" src="{{ $shop->logo }}" />
                                    </div>
                                </td>
                                <td>{{ $shop->name }}</td>
                                <td>
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Click here to change status')}}">
                                        <a href="{{ $isRoot ? '#' : route('admin.shop.status.toggle', $shop->id) }}">
                                            <input type="checkbox" {{ $shop->user?->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.shop.products', $shop->id) }}" class="badge badge-square badge-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{__('Click here to view total products')}}">
                                        {{ $shop->products->count() }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.shop.orders', $shop->id) }}"
                                        class="badge badge-square badge-info" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{__('Click here to view total orders')}}">
                                        {{ $shop->orders->count() }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-outline-primary circleIcon"
                                        href="{{ route('admin.shop.show', $shop->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.shop.edit', $shop->id) }}"
                                        class="btn btn-outline-secondary circleIcon" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="my-3">
            {{ $shops->links() }}
        </div>
    </div>
@endsection
