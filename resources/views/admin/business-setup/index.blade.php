@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-buildings"></i> {{__('Business Settings')}}
        </div>
    </div>

    <div class="mt-4">
        @include('admin.business-setup.header')
    </div>

    <form action="{{ route('admin.business-setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!--######## Basic Information ##########-->
        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                    <i class="bi bi-briefcase-fill"></i>
                    <h5 class="mb-0">{{__('Business Information')}}</h5>
                </div>

                <div class="row">

                    <div class="col-lg-4 mt-4">
                        <x-input type="text" label="Company Name" name="name"
                            placeholder="Enter Company Name / Business Name" :value="$generaleSetting?->name" />
                    </div>

                    <div class="col-lg-4 mt-4">
                        <x-input type="text" label="Company Email" name="email" placeholder="Enter Company Email"
                            :value="$generaleSetting?->email" />
                    </div>

                    <div class="col-lg-4 mt-4">
                        <x-input type="text" label="Company Phone" name="mobile" placeholder="Enter Company Phone"
                            :value="$generaleSetting?->mobile" />
                    </div>

                    @php
                        $businessType = $generaleSetting?->shop_type ?? 'multi';
                    @endphp

                    <div class="col-lg-4 mt-4">
                        <label class="form-label">{{__('Business Model')}}</label>
                        <div class="d-flex flex-wrap align-items-center gap-5 border rounded fw-medium"
                            style="padding: 10px;">
                            <div class="flex-grow-1">
                                <input type="radio" name="shop_type" value="single" class="form-check-input"
                                    id="single" {{ $businessType == 'single' ? 'checked' : '' }}>
                                <label for="single" class="m-0 cursor-pointer">
                                    {{__('Singel Shop')}}
                                </label>
                            </div>

                            <div class="flex-grow-1 d-flex align-items-center gap-2">
                                <input type="radio" name="shop_type" value="multi" class="form-check-input"
                                    id="multi" {{ $businessType == 'multi' ? 'checked' : '' }}>
                                <label for="multi" class="m-0 cursor-pointer">
                                    {{__('Multi Shop')}}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label class="form-label">
                            {{ __('Currency Position') }}
                        </label>
                        <div class="d-flex flex-wrap align-items-center gap-5 border rounded fw-medium"
                            style="padding: 10px;">
                            <div class="flex-grow-1">
                                <input type="radio" name="currency_position" value="prefix" class="form-check-input"
                                    id="prefix" {{ $generaleSetting?->currency_position == 'prefix' ? 'checked' : '' }}>
                                <label for="prefix" class="m-0">($) {{ __('Left') }}</label>
                            </div>

                            <div class="flex-grow-1 d-flex align-items-center gap-2">
                                <input type="radio" name="currency_position" value="suffix" class="form-check-input"
                                    id="suffix" {{ $generaleSetting?->currency_position == 'suffix' ? 'checked' : '' }}>
                                <label for="suffix" class="m-0">{{__('Right')}} ($)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <x-select name="timezone" label="Time Zone">
                            @foreach ($timezones as $timezone)
                                <option value="{{ $timezone['zone'] }}"
                                    {{ config('app.timezone') == $timezone['zone'] ? 'selected' : '' }}>
                                    {{ $timezone['diff_from_GMT'] . ' - ' . $timezone['zone'] }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                </div>

            </div>
        </div>


        <!--######## Theme Settings ##########-->
        <div class="row">
            <div class="col-lg-4">

                <div class="card mt-4">
                    <div class="card-body">

                        <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                            <i class="bi bi-palette-fill"></i>
                            <h5 class="mb-0">
                                {{__('Theme Color Settings')}}
                            </h5>
                        </div>

                        <div class="d-flex align-items-center gap-5 mt-4 flex-wrap">
                            <div class="color-panel">
                                <span class="color-input"
                                    style="background: {{ $generaleSetting?->primary_color ?? '#8b5cf6' }}"></span>
                                <p class="color">
                                    {{ $generaleSetting?->primary_color ?? '#8b5cf6' }}
                                </p>
                                <label for="primary_color" class="color-label">
                                    {{ __('Primary Color') }}
                                </label>
                            </div>

                            <div class="color-panel">
                                <span class="color-input"
                                    style="background: {{ $generaleSetting?->secondary_color ?? '#ede9fe' }}"></span>
                                <p class="color">
                                    {{ $generaleSetting?->secondary_color ?? '#ede9fe' }}
                                </p>
                                <label for="secondary_color" class="color-label">
                                    {{ __('Secondary Color') }}
                                </label>
                            </div>
                        </div>

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
