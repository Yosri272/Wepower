@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-buildings"></i> {{ __('Business Settings') }}
        </div>
    </div>

    <div class="mt-3">
        @include('admin.business-setup.header')
    </div>

    <form action="{{ route('admin.business-setting.shop.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!--######## Basic Information ##########-->
        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                    <i class="bi bi-shop"></i>
                    <h5 class="mb-0">{{ __('Shop Setup') }}</h5>
                </div>

                <div class="row">

                    <div class="col-lg-4 mt-3">
                        <label for="" class="form-label">{{ __('Commission') }}</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Enter Commission" name="commission"
                                value="{{ $generaleSetting?->commission }}"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            <span class="input-group-text" id="type">
                                {{ $generaleSetting?->commission_type == 'percentage' ? '%' : '$' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-3">
                        <label for="" class="form-label">{{ __('Commission Type') }}</label>
                        <select name="commission_type" id="commissionType" class="form-control form-select">
                            <option value="percentage" id="percentage"
                                {{ $generaleSetting?->commission_type == 'percentage' ? 'selected' : '' }}{{ $generaleSetting?->commission_charge == 'monthly' ? 'disabled' : '' }}>
                                {{ __('Percentage') }}
                            </option>
                            <option value="fixed" {{ $generaleSetting?->commission_type == 'fixed' ? 'selected' : '' }}>
                                {{__('Fixed Amount')}}
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-4 mt-3">
                        <label for="" class="form-label">{{ __('Commission Charge') }}</label>
                        <select name="commission_charge" id="commissionCharge" class="form-control form-select">
                            <option value="per_order"
                                {{ $generaleSetting?->commision_charge == 'per_order' ? 'selected' : '' }}>
                                {{ __('Per Order') }}
                            </option>
                            <option value="monthly"
                                {{ $generaleSetting?->commission_charge == 'monthly' ? 'selected' : '' }}>
                                {{ __('Monthly') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-4 mt-3">
                        <div class="border rounded py-2.5 px-3 d-flex align-items-center justify-content-between">
                            <span>
                                {{__('Enable POS in Shop Panel')}}
                            </span>
                            <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="Enable/Disable">
                                <a href="{{ route('admin.business-setting.shop.toggle-pos') }}" class="confirm">
                                    <input type="checkbox" {{ $generaleSetting?->shop_pos ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-3">
                        <div class="border rounded py-2.5 px-3 d-flex align-items-center justify-content-between">
                        <span>{{ __('Shop Registration') }}</span>
                            <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="Enable/Disable">
                                <a href="{{ route('admin.business-setting.shop.toggle-register') }}" class="confirm">
                                    <input type="checkbox" {{ $generaleSetting?->shop_register ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                    <i class="bi bi-box-seam"></i>
                    <h5 class="mb-0">
                        {{ __('Need Product Approval') }}
                    </h5>
                </div>

                <div class="mt-3 d-flex gap-4 flex-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="new_product_approval" id="defaultCheck1"
                            {{ $generaleSetting?->new_product_approval ? 'checked' : '' }} />
                        <label class="form-check-label" for="defaultCheck1">
                           {{__('Need Product Approval')}}
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="update_product_approval" id="defaultCheck2"
                            {{ $generaleSetting?->update_product_approval ? 'checked' : '' }} />
                        <label class="form-check-label" for="defaultCheck2">
                            {{__('Update Product Approval')}}
                        </label>

                        <span class="text text-info">({{__('when shop update any filed of product it will be nedded to approve')}})</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 mb-3">
            <button type="submit" class="btn btn-primary py-2 px-3">
                {{__('Save And Update')}}
            </button>
        </div>

    </form>
@endsection

@push('scripts')
    <script>
        $('#commissionType').change(function() {
            if ($(this).val() == 'percentage') {
                $('#type').text('%');
            } else {
                $('#type').text('$');
            }
        });

        $('#commissionCharge').change(function() {
            if ($(this).val() == 'per_order') {
                $('#commissionType').val('percentage');
                $('#percentage').removeAttr('disabled');
                $('#type').text('%');
            } else {
                $('#commissionType ').val('fixed');
                $('#percentage').prop('disabled', 'disabled');
                $('#type').text('$');
            }
        });

        $(".confirm").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to change status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
