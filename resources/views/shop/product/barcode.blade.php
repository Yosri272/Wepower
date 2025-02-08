@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            <i class="bi bi-box-seam-fill"></i>
            {{__('Generate Barcode')}}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="card my-3">
            <div class="card-body">

                <div class="d-flex gap-2 pb-2">
                    <h5></h5>
                </div>

                <form action="" method="GET">
                    <div class="table-responsive">
                        <table class="table table-borderless table-responsive-lg">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">{{ __('Code') }}</th>
                                    <th class="py-3">{{ __('Name') }}</th>
                                    <th class="py-3">{{ __('Quantity') }}</th>
                                    <th class="py-3 text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $product->code }}</td>

                                    <td>
                                        <div class="productName">
                                            {{ Str::limit($product->name, 30) }}
                                        </div>
                                    </td>

                                    <td>
                                        <x-input type="text" name="qty" :value="$quantitys" onlyNumber="true" />
                                    </td>

                                    <td class="text-center" style="max-width: 200px">
                                        <div class="d-flex gap-2 justify-content-center">

                                            <button type="submit" class="btn btn-outline-primary py-2 px-4">
                                                {{ __('Generate Barcode') }}
                                            </button>

                                            <a href="{{ route('shop.product.barcode', $product->id) }}" class="btn btn-outline-danger py-2 px-4">
                                                {{__('Reset')}}
                                            </a>

                                            <button type="button" class="btn btn-outline-success py-2 px-4"
                                                onclick="print()">
                                                {{__('Print')}}
                                            </button>

                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
        </div>

        <div class="my-4">

            <div class="barcodeBox" id="printelement">
                @for ($i = 0; $i < $quantitys; $i++)
                    <div class="productBarcode">
                        <div class="siteName">{{ $generaleSetting->name ?? 'Razin Commerce' }}</div>
                        <div class="name">{{ $product->name }}</div>
                        <div class="price">
                            {{ $generaleSetting->currency ?? '$' }}{{ number_format($product->price, 2) }}
                        </div>
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->code, 'I25', 3, 28) }}"  alt="barcode" />
                        <div class="code">{{ __('Code') }}: {{ $product->code }}</div>
                    </div>
                @endfor
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        function print() {
            $("#printelement").print();
        }
    </script>
@endpush
