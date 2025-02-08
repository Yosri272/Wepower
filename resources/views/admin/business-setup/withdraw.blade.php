@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-buildings"></i> {{__('Business Settings')}}
        </div>
    </div>

    <div class="mt-3">
        @include('admin.business-setup.header')
    </div>

    <form action="{{ route('admin.business-setting.withdraw.update') }}" method="POST">
        @csrf

        <!--######## withdraw Information ##########-->
        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                    <i class="bi bi-wallet2"></i>
                    <h5 class="mb-0">
                        {{__('Withdraw Setup')}}
                    </h5>
                </div>

                <div class="row">

                    <div class="col-lg-4 mt-3">
                        <x-input type="text" name="min_withdraw" label="Min Withdraw Amount" :value="$generaleSetting?->min_withdraw ?? 0" onlyNumber="true" />
                    </div>

                    <div class="col-lg-4 mt-3">
                        <x-input type="text" name="max_withdraw" label="Max Withdraw Amount" :value="$generaleSetting?->max_withdraw" onlyNumber="true" />
                    </div>

                    <div class="col-lg-4 mt-3">
                        <label for="" class="form-label">
                            {{__('Min Day Withdraw Request')}}
                        </label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Enter min day" name="withdraw_request"
                                value="{{ $generaleSetting?->withdraw_request }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            <span class="input-group-text">Days</span>
                        </div>
                    </div>


                </div>

            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                    <i class="bi bi-wallet2"></i>
                    <h5 class="mb-0">{{__('Withdrawal Notes')}}</h5>
                </div>

                <div class="mt-3">
                    <p><strong>{{__('Minimum Withdrawal Amount')}}:</strong></p>
                    <ul>
                        <li>Enter the minimum amount that can be withdrawn. This value must be a numerical figure.</li>
                        <li>Example: If the minimum withdrawal amount is set to $10, users cannot withdraw any amount less than $10.</li>
                    </ul>

                    <p><strong>{{__('Maximum Withdrawal Amount')}}:</strong></p>
                    <ul>
                        <li>Enter the maximum amount that can be withdrawn at a time. This value must be a numerical figure.</li>
                        <li>Example: If the maximum withdrawal amount is set to $1,000, users cannot withdraw more than $1,000 in a single transaction.</li>
                    </ul>

                    <p><strong>
                        {{__('Minimum Days Between Withdrawal Requests')}}:
                    </strong></p>
                    <ul>
                        <li>Specify the minimum number of days required between withdrawal requests. This value should be an integer.</li>
                        <li>Example: If set to 7 days, after a seller sends a withdrawal request, they must wait at least 7 days before sending another request.</li>
                    </ul>
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
