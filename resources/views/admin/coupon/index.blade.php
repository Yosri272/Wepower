@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4> {{ __('Coupons') }} </h4>

        <a href="{{ route('admin.coupon.create') }}" class="btn py-2 btn-primary">
            <i class="bi bi-patch-plus"></i>
           {{__('Create New')}}
        </a>

    </div>

    <div class="mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card rounded-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Code') }}</th>
                                        <th>{{ __('Discount') }}</th>
                                        <th>{{ __('Min Amount') }}</th>
                                        <th>{{ __('Started At') }}</th>
                                        <th>{{ __('Expired At') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($coupons as $coupon)
                                        <tr>
                                            <td>{{ $coupon->code }}</td>
                                            <td>
                                                {!! $coupon->type->value == 'Amount' ? showCurrency($coupon->discount) : $coupon->discount . '%' !!}
                                            </td>
                                            <td>
                                                {{ showCurrency($coupon->min_amount) }}
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($coupon->started_at)->format('M d, Y h:i a') }}
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($coupon->expired_at)->format('M d, Y h:i a') }}
                                            </td>
                                            <td>
                                                <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                                    data-bs-title="{{__('Status Update')}}">
                                                    <a href="{{ route('admin.coupon.toggle', $coupon->id) }}">
                                                        <input type="checkbox" {{ $coupon->is_active ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </a>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}"
                                                    class="btn btn-outline-primary circleIcon" data-bs-toggle="tooltip"
                                                    data-bs-placement="left" data-bs-title="{{__('Edit')}}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="{{ route('admin.coupon.destroy', $coupon->id) }}" class="btn btn-outline-danger circleIcon deleteConfirm"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    data-bs-title="{{__('Delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{ $coupons->links() }}

            </div>
        </div>
    </div>
@endsection
