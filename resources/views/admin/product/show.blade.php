@extends('layouts.app')

@section('content')
    <div>
        <h4>
            {{ __('Product Details') }}
        </h4>
    </div>


    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-3">
                <div class="text-center">
                    <div class="rounded overflow-hidden ratio1x1">
                        <img src="{{ $product->thumbnail }}" alt="" width="140">
                    </div>
                    <a href="/products/{{ $product->id }}/details" target="_blank" class="btn btn-outline-primary mt-3">
                        <i class="fa-solid fa-globe"></i> {{__('View Live')}}
                    </a>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap gap-3 justify-content-between">
                        <div class="d-flex gap-3 productThumbnail">
                            @foreach ($product->thumbnails() as $photo)
                                <img src="{{ $photo->thumbnail }}" alt="product" />
                            @endforeach
                        </div>

                        <div>
                            <div class="d-flex gap-3 border p-2 rounded fw-bold">
                                <div>{{ $product->orders->count() }} {{__('Orders')}}</div>

                                <div class="border-start w-0" style="height: 20px"></div>

                                <div>
                                    <i class="fa-solid fa-star text-warning"></i>
                                    {{ number_format($product->reviews->avg('rating'), 1) }}
                                </div>

                                <div class="border-start w-0" style="height: 20px"></div>

                                <div>{{ number_format($product->reviews->count(), 1) }} {{__('Reviews')}}</div>
                            </div>
                            <div class="mt-2">
                                <div>
                                    {{ __('status') }}:
                                    {{ __('status') }}:
                                    @if ($product->is_approve)
                                        <span class="status-approved">
                                            <i class="fa fa-check text-success"></i> {{ __('Approved') }}
                                        </span>
                                    @else
                                        <span class="status-pending">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ __('Pending') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>
                    <h3 class="mb-2 mt-3 pb-1">{{ $product->name }}</h3>

                    <div>
                        <h6 class="mb-1 text-muted">
                            {{ __('Short Description') }}
                        </h6>
                        <p>{{ $product->short_description }}</p>
                    </div>
                </div>
            </div>

            <div class="border-top my-3"></div>

            <!-- General Information -->
            <div class="d-flex gap-4 flex-wrap justify-content-lg-between">

                <div>
                    <h5 class="text-dark fw-bold">
                        {{ __('General Information') }}
                    </h5>
                    <table class="table table-borderless mb-0 border-0">
                        <tr>
                            <td class="ps-0 py-1">{{ __('Brand') }}</td>
                            <td class="py-1">:{{ __($product->brand?->name) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 py-1">{{ __('Categories') }}</td>
                            <td class="py-1">
                                :@foreach ($product->categories as $category)
                                    {{ __($category->name) }}@if (!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0 py-1">{{ __('Colors') }}</td>
                            <td class="py-1">
                                :@foreach ($product->colors as $color)
                                    {{ __($color->name) }}@if (!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0 py-1">{{ __('Sizes') }}</td>
                            <td class="py-1">
                                :@foreach ($product->sizes as $size)
                                    {{ __($size->name) }}@if (!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h5 class="text-dark fw-bold">{{ __('Price Information') }}</h5>
                    <table class="table table-borderless mb-0 border-0">
                        <tr>
                            <td class="ps-0 py-1">{{ __('Price') }}</td>
                            <td class="py-1">: {{ showCurrency($product->price) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 py-1">{{ __('Discount Price') }}</td>
                            <td class="py-1">
                               : {{ showCurrency($product->discount_price) }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div>
                    <h5 class="text-dark fw-bold"> {{__('Tags')}}</h5>
                    <p>
                        Secret Covered in Sand Comic book pdf , comic , book
                    </p>
                </div>
            </div>

            <div class="border-top my-3"></div>

            <div>
                <h5 class="text-dark fw-bold">
                    {{ __('Description') }}
                </h5>
                <p>
                    {!! $product->description !!}
                </p>
            </div>

        </div>
    </div>
@endsection
