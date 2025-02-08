@extends('layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    {{ __('Draft') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="cardTitleBox">
                <h5 class="card-title chartTitle">
                    {{ __('Draft Items') }}
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-responsive-lg">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Total Products') }}</th>
                            <th>{{ __('Sub Total') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($postCarts as $postCart)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $postCart->created_at->format('d M Y, h:i A') }}
                                    <br>
                                    <small>
                                        {{ $postCart->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    {{ $postCart?->user?->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $postCart->products->count() }}
                                    </span>
                                    {{ __('Items') }}
                                </td>
                                <td>
                                    {{ showCurrency($postCart->subtotal) }}
                                </td>
                                <td>
                                    {{ showCurrency($postCart->discount) }}
                                </td>
                                <td>
                                    {{ showCurrency($postCart->total) }}
                                </td>
                                <td>
                                    <a href="{{ route('shop.pos.index','name='.$postCart->name) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="{{__('Edit')}}"
                                        class="circleIcon btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

@endsection
