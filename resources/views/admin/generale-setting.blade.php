@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-gear-fill"></i> {{ __('Admin Settings') }}
        </div>
    </div>
    <form action="{{ route('admin.generale-setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mt-3">
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="">
                            <x-input type="text" label="Website Name" name="name" placeholder="Enter Website Name"
                                :value="$generaleSetting?->name" />
                        </div>

                        <div class="mt-4">
                            <x-input label="Website Title" name="title" type="text"
                                placeholder="Enter Website Title for title bar" :value="$generaleSetting?->title" />
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <x-input label="Currency Symbol" name="currency" type="text"
                                    placeholder="Enter Currency Symbol for price" :value="$generaleSetting?->currency" />
                            </div>

                            <div class="col-sm-6 mt-4 mt-sm-0">
                                <x-select label="Currency Position" name="currency_position">
                                    <option value="prefix"
                                        {{ $generaleSetting?->currency_position == 'prefix' ? 'selected' : '' }}>
                                        {{ __('Prefix') }}
                                    </option>
                                    <option value="suffix"
                                        {{ $generaleSetting?->currency_position == 'suffix' ? 'selected' : '' }}>
                                        {{ __('Suffix') }}
                                    </option>
                                </x-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-6 mt-4 mt-sm-5">
                                <div class="mt-3 d-flex align-items-center justify-content-center">
                                    <div class="logoratio">
                                        <img id="previewLogo"
                                            src="{{ $generaleSetting?->logo ?? 'https://placehold.co/200x50/png' }}"
                                            alt="" width="100%" loading="lazy" />
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <x-file name="logo" label="Logo Ratio4:1 (200x50)" preview="previewLogo" />
                                </div>
                            </div>

                            <div class="col-sm-6 mt-4">
                                <div class="mt-3 d-flex align-items-center justify-content-center">
                                    <div class="logoFav">
                                        <img id="previewFavicon"
                                            src="{{ $generaleSetting?->favicon ?? 'https://placehold.co/300x300/png' }}"
                                            alt="" width="100%" loading="lazy" />
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <x-file name="favicon" label="Favicon (300x300)" preview="previewFavicon" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-6 mt-4">
                        <div class="mt-3 d-flex align-items-center justify-content-center">
                            <div class="logoFav">
                                <img id="previewAppIcon"
                                    src="{{ $generaleSetting?->appLogo ?? 'https://placehold.co/300x300/png' }}"
                                    alt="" width="100%" loading="lazy" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <x-file name="app_logo" label="App Logo (300x300)" preview="previewAppIcon" />
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!--######## Others Information ##########-->
        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex align-items-center gap-2 border-bottom pb-2">
                    <i class="bi bi-app-indicator"></i>
                    <h5 class="mb-0">
                        {{ __('Others Information') }}
                    </h5>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-4 col-md-6">
                        <x-input type="number" name="mobile" label="Mobile Number" placeholder="Enter Mobile Number"
                            :value="$generaleSetting?->mobile" />
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <x-input type="email" name="email" label="Email Address" placeholder="Enter Email Address"
                            :value="$generaleSetting?->email" />
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <x-input type="text" name="address" label="Address" placeholder="Enter Address"
                            :value="$generaleSetting?->address" />
                    </div>
                    
                </div>
            </div>
        </div>

        <!--######## download app link ##########-->
        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex align-items-center justify-content-between gap-2 border-bottom pb-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-app-indicator"></i>
                        <h5 class="mb-0">
                            {{ __('Download App Link') }}
                        </h5>
                    </div>

                    <div>
                        <label class="m-0 fw-bold" for="toggle">
                            {{ __('Show/Hide Download App') }}
                        </label>
                        <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Show/Hide">
                            <input id="toggle" type="checkbox" {{ $generaleSetting?->show_download_app ? 'checked' : '' }}
                                name="show_download_app">
                            <span class="slider round"></span>
                        </label>
                    </div>

                </div>
                <div class="row mt-3">

                    <div class="col-md-6 mt-3">
                        <label for="" class="mb-1">
                            {{ __('Google Playstore App Link') }}
                        </label>
                        <textarea name="google_playstore_url" class="form-control" rows="3"
                            placeholder="Enter Google Playstore App Link">{{ $generaleSetting?->google_playstore_url }}</textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="" class="mb-1">
                            {{ __('Apple Store App Link') }}
                        </label>
                        <textarea name="app_store_url" class="form-control" rows="3" placeholder="Enter Apple Store App Link">{{ $generaleSetting?->app_store_url }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <!--######## Footer Information ##########-->
        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex align-items-center justify-content-between gap-2 border-bottom pb-2">
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-align-bottom"></i>
                        <h5 class="mb-0">
                            {{ __('Footer Section Info') }}
                        </h5>
                    </div>

                    <div>
                        <label class="m-0 fw-bold" for="toggle">
                            {{ __('Show/Hide Footer Section') }}
                        </label>
                        <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Show/Hide">
                            <input id="toggle" type="checkbox" {{ $generaleSetting?->show_footer ? 'checked' : '' }}
                                name="show_footer">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-4 col-md-6">
                        <x-input type="number" name="footer_phone" label="Footer Mobile Number"
                            placeholder="Enter Mobile Number" :value="$generaleSetting?->footer_phone" />
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <x-input type="email" name="footer_email" label="Footer Email Address"
                            placeholder="Enter Email Address" :value="$generaleSetting?->footer_email" />
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <x-input type="text" name="footer_text" label="Footer Text" placeholder="Enter Footer Text"
                            :value="$generaleSetting?->footer_text ?? 'All right reserved by RazinSoft'" />
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4">
                        <label class="mb-1">
                            {{ __('Frontend Footer Short Description') }}
                        </label>
                        <textarea name="footer_description" class="form-control" rows="3"
                            placeholder="{{ __('Frontend Footer Short Description') }}">{{ $generaleSetting?->footer_description ?? 'The ultimate all-in-one solution for your eCommerce business worldwide.' }}</textarea>
                    </div>

                    <div class="col-md-6 col-lg-4 mt-4">
                        <div class="mt-4 d-flex align-items-center justify-content-center">
                            <div class="logoratio">
                                <img id="previewFooterLogo"
                                    src="{{ $generaleSetting?->footerLogo ?? 'https://placehold.co/200x50/png' }}"
                                    alt="" width="100%" loading="lazy" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <x-file name="footer_logo" label="Frontend Footer Logo Ratio4:1"
                                preview="previewFooterLogo" />
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mt-4">
                        <div class="mt-2 d-flex align-items-center justify-content-center">
                            <div class="logoFav">
                                <img id="footerqrcode"
                                    src="{{ $generaleSetting?->footerQr ?? 'https://placehold.co/200x200/png' }}"
                                    alt="" width="100%" loading="lazy" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <x-file name="footer_qrcode" label="Frontend Scan the QR (200x200)" preview="footerqrcode" />
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 mb-3">
            <button type="submit" class="btn btn-primary py-2 px-3">
                {{ __('Save And Update') }}
            </button>
        </div>

    </form>
@endsection
