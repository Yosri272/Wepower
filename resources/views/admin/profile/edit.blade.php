@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-user"></i>{{__('Edit Profile')}}
        </div>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-9 m-auto">

                <div class="card mt-3">
                    <div class="card-body">

                        <div class="d-flex gap-2 border-bottom pb-2">
                            <i class="fa-solid fa-user"></i>
                            <h5>{{__('User Information')}}</h5>
                        </div>

                        <div class="row">
                            <div class="col-lg-7">
                                <div class="mt-3">
                                    <x-input label="Full Name" name="name" type="text" placeholder="Full Name"
                                        :value="auth()->user()->name" />
                                </div>

                                <div class="mt-3">
                                    <x-input label="Phone Number" name="phone" type="number"
                                        placeholder="Enter phone number" :value="auth()->user()->phone" />
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <x-select label="Gender" name="gender">
                                                <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>
                                                    {{__('Male')}}
                                                </option>
                                                <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>
                                                    {{__('Female')}}
                                                </option>
                                                <option value="other" {{ auth()->user()->gender == 'other' ? 'selected' : '' }}>
                                                    {{__('Other')}}
                                                </option>
                                            </x-select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <x-input label="Date of Birth" name="date_of_birth" type="date" :value="auth()->user()->date_of_birth" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    @php
                                        $isLocal = app()->isLocal() ? 'readonly=true' : '';
                                    @endphp
                                    <x-input type="email" name="email" label="Email" placeholder="Enter Email Address" :value="auth()->user()->email" :readonly="$isLocal" />
                                </div>

                            </div>
                            <div class="col-lg-5">
                                <div class="mt-3 mt-lg-5 d-flex align-items-center justify-content-center">
                                    <div class="ratio1x1 mt-lg-5">
                                        <img id="previewProfile"
                                            src="{{ auth()->user()->thumbnail ?? asset('defualt/defualt.jpg') }}"
                                            alt="" width="100%">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <x-file name="profile_photo" label="User profile (Ratio 1:1)" preview="previewProfile" />
                                </div>
                            </div>
                        </div>


                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.profile.index') }}" class="btn btn-light py-2 px-4">
                                <i class="bi bi-arrow-left"></i> {{__('Back')}}
                            </a>
                            <button type="submit" class="btn btn-primary py-2 px-4">
                                {{__('Update')}}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
