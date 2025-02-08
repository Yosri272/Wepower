@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4 my-md-0">
        <div class="row d-flex align-items-center h-100vh">
            <div class="col-md-8 col-lg-7 m-auto">

                <form action="{{ route('shop.profile.change-password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card shadow rounded-12 border-0">
                        <div class="card-header py-3">
                            <h4 class="m-0">{{ __('Change Password') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <x-input name="current_password" type="text" placeholder="Change Password"
                                    label="Current Password" />
                            </div>

                            <div class="form-group mt-3">
                                <x-input name="password" type="text" placeholder="New Password" label="New Password" />
                            </div>
                            <div class="form-group mt-3">
                                <x-input name="password_confirmation" type="text" placeholder="Enter Confirm Password"
                                    label="Confirm Password" />
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between py-3">
                            <button type="submit" class="btn btn-primary py-2">
                                {{ __('Update Password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
