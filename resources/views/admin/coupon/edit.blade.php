@extends('layouts.app')

@section('content')

    <div class="page-title">
        <div class="d-flex gap-2 align-items-center text-muted">
            <i class="bi bi-pencil-square"></i> {{ __('Edit Voucher') }}
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-9 col-md-10 m-auto">
            <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                        <h4 class="card-title m-0">
                            <i class="bi bi-pencil-square"></i> {{ __('Edit Voucher') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <x-select name="shops[]" label="{{__('Select shops')}}" placeholder="{{__('Select shops')}}" multiselect="true">
                                    <option value="">{{__('Select shops')}}</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}" @if (in_array($shop->id, $coupon->shops->pluck('id')->toArray())) selected @endif>
                                            {{ $shop->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <x-input name="code" type="text" placeholder="Coupon code" label="Voucher Code"  :value="$coupon->code"/>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <x-select name="discount_type" label="Discount Type">
                                    @foreach ($discountTypes as $type)
                                        <option value="{{ $type->value }}" @if ($coupon->type->value == $type->value) selected @endif>
                                            {{ __($type->value) }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-12 col-md-6">
                                <x-input name="discount" label="Discount" type="text" placeholder="Discount" :value="$coupon->discount"
                                    onlyNumber="true" />
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <x-input name="min_order_amount" type="text" placeholder="Minimum Order Amount" :value="$coupon->min_amount"
                                    label="Minimum Order Amount" onlyNumber="true" />
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <x-input name="limit_for_user" label="Limit For Single User" type="text" placeholder="exm: 5" onlyNumber="true" :value="$coupon->limit_for_user" />
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <x-input name="max_discount_amount" label="Maximum Discount Amount" type="text" placeholder="exm: $300" onlyNumber="true" :value="$coupon->max_discount_amount"/>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <x-input type="date" label="Start Date" name="start_date" :value="Carbon\Carbon::parse($coupon->started_at)->format('Y-m-d')" />
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <x-input type="time" label="Start Time" name="start_time" :value="Carbon\Carbon::parse($coupon->started_at)->format('H:i')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <x-input type="date" label="Expired Date" name="expired_date" :value="Carbon\Carbon::parse($coupon->expired_at)->format('Y-m-d')" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-input type="time" label="Expired Time" name="expired_time" :value="Carbon\Carbon::parse($coupon->expired_at)->format('H:i')" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer py-3 d-flex justify-content-between flex-wrap gap-2">
                        <a href="{{ route('admin.coupon.index') }}" class="btn btn-light px-4 py-2">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
