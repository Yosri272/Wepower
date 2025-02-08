@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Bulk Product Exports ') }}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="card" style="border-color: rgba(231, 234, 243, 0.5019607843);">
            <div class="card-body">
                <div class="row g-4">
                    <!-- Step 1 -->
                    <div class="col-lg-6 col-xl-4">
                        <div class="export-steps-item h-100">
                            <div class="d-flex gap-3 justify-content-between align-items-center">
                                <div>
                                    <h3 class="fz-20 text-dark">
                                        {{ __('Step 1') }}
                                    </h3>
                                    <div>
                                        {{__('Select Data Type')}}
                                    </div>
                                </div>
                                <img src="{{ asset('assets/images/bulk-export-1.png') }}" alt="">
                            </div>

                            <h4 class="mt-3 text-dark fz-20">
                                {{__('Instruction')}}
                            </h4>

                            <ul class="m-0 pl-4">
                                <li>
                                    {{__('Choose the data type to specify the order in which you want your data sorted when downloading.')}}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-lg-6 col-xl-4">
                        <div class="export-steps-item h-100">
                            <div class="d-flex gap-3 justify-content-between align-items-center">
                                <div>
                                    <h3 class="fz-20 text-dark">
                                        {{ __('Step 2') }}
                                    </h3>
                                    <div>
                                        {{__('Select Data Range by All and Export')}}
                                    </div>
                                </div>
                                <img src="{{ asset('assets/images/bulk-export-2.png') }}" alt="">
                            </div>

                            <h4 class="mt-3 text-dark fz-20">
                                {{__('Instruction')}}
                            </h4>

                            <ul class="m-0 pl-4">
                                <li>
                                    {{_('When you download the file, it will be in .xlsx format.')}}
                                </li>
                                <li>
                                    {{_("Click 'Reset' if you want to discard your changes and download the data sorted in the default order.")}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 pb-2 mt-4">
                    <h5>
                        <i class="fa-solid fa-file-import"></i>
                        {{ __('Export Products') }}
                    </h5>
                </div>

                <form action="{{ route('shop.bulk-product-export.export') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="type" class="form-label">
                                {{ __('Type') }}
                            </label>
                            <select name="type" id="type" class="form-select form-control">
                                <option value="all">
                                    {{ __('All Products') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3 justify-content-end">
                        <button type="reset" class="btn btn-secondary py-2 px-3">
                            {{ __('Reset') }}
                        </button>
                        <button type="submit" class="btn btn-primary py-2 px-3">
                            {{ __('Export') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection
@push('css')
    <style>
        .export-steps-item ul li {
            margin-bottom: 6px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/scripts/drop-zone.js') }}"></script>
@endpush
