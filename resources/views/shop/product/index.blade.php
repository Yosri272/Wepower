@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Product List') }}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="card my-3">
            <div class="card-body">

                <div class="d-flex gap-2 pb-2">
                    <h5>
                        {{ __('Filter Products') }}
                    </h5>
                </div>

                <form action="" method="GET">
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <x-select label="Category" name="category" placeholder="Select Category">
                                <option value="">
                                    {{ __('Select Category') }}
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-select label="Brand" name="brand" placeholder="All Brand">
                                <option value="">
                                    {{ __('All Brand') }}
                                </option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-select label="Color" name="color" placeholder="All Color">
                                <option value="">
                                    {{ __('All Color') }}
                                </option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}"
                                        {{ request('color') == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                    </div>

                    <div class="mt-2 d-flex gap-2 justify-content-end">
                        <button type="reset" class="btn btn-light py-2 px-4">
                            {{ __('Reset') }}
                        </button>
                        <button type="submit" class="btn btn-primary py-2 px-4">
                            {{ __('Filter Data') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-3 card">
            <div class="card-body">

                <form action="" class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <div class="input-group" style="max-width: 400px">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Search by product name') }}" value="{{ request('search') }}">
                        <button type="submit" class="input-group-text btn btn-primary">
                            <i class="fa fa-search"></i> {{ __('Search') }}
                        </button>
                    </div>
                    <a href="{{ route('shop.product.create') }}" class="btn py-2 btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        {{__('Create New')}}
                    </a>
                </form>

                <div class="table-responsive">
                    <table class="table border table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th class="text-center">{{ __('Price') }}</th>
                                <th class="text-center">{{ __('Discount Price') }}</th>
                                <th class="text-center">{{ __('Verify Status') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($products as $key => $product)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>
                                    <div class="product-image">
                                        <img src="{{ $product->thumbnail }}">
                                    </div>
                                </td>

                                <td>{{ Str::limit($product->name, 50, '...') }}</td>

                                <td class="text-center">
                                    {{ showCurrency($product->price) }}
                                </td>

                                <td class="text-center">
                                    {{ showCurrency($product->discount_price) }}
                                </td>

                                <td class="text-center">
                                    @if ($product->is_approve)
                                        <span class="status-approved">
                                            <i class="fa fa-check-circle text-success"></i> {{ __('Approved') }}
                                        </span>
                                    @else
                                        <span class="status-pending" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Your product status is pending because admin hasn't approved it. When admin will approve your product, it will be show as approved.">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ __('Pending') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <label class="switch mb-0" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="{{__('Update product status')}}">
                                        <a href="{{ route('shop.product.toggle', $product->id) }}">
                                            <input type="checkbox" {{ $product->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">

                                        <a href="{{ route('shop.product.show', $product->id) }}" class="btn btn-outline-primary circleIcon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{__('View Product')}}">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>

                                        <a href="{{ route('shop.product.barcode', $product->id) }}" class="btn btn-outline-info circleIcon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{__('Generate Barcode for this product')}}">
                                            <i class="bi bi-upc-scan"></i>
                                        </a>

                                        <a href="{{ route('shop.product.edit', $product->id) }}" class="btn btn-outline-primary circleIcon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="{{__('Edit Product')}}">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $products->links() }}
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        $(".confirmApprove").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to approve this product",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Approve it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
