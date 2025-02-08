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
                                <th>{{ __('Thumbnail') }}</th>
                                <th style="min-width: 120px">{{ __('Product Name') }}</th>
                                <th style="min-width: 280px">{{ __('Review') }}</th>
                                <th>{{ __('Rating') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="customar-image">
                                            <img src="{{ $review->product?->thumbnail }}" alt="" width="50">
                                        </div>
                                    </td>
                                    <td>{{ $review->product?->name }}</td>
                                    <td>{{ $review->description }}</td>
                                    <td>
                                        <i class="fa fa-star text-warning"></i>
                                        {{ number_format($review->rating, 1) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

        <div class="my-3">
            {{ $reviews->links() }}
        </div>

    </div>
@endsection
