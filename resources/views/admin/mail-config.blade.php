@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-8 col-lg-9 mx-auto ">
                <form action="{{ route('admin.mailConfig.update') }}" method="POST">
                    @method('put')
                    @csrf
                    <div class="card">
                        <div class="card-header py-3">
                            <h4 class="m-0">{{ __('Mail Configuration') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_mailer')" name="mailer" type="text" placeholder="smtp" label="{{ __('Mail Mailer') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_host')" name="host" type="text" placeholder="ex: smtp.gmail.com" label="{{ __('Mail Host') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_port')" name="port" type="text" placeholder="ex: 465" label="{{ __('Mail Port') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_username')" name="username" type="text" placeholder="ex: example@gmail.com" label="{{ __('Mail User Name') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_password')" name="password" type="text" placeholder="Your app password" label="{{ __('Mail Password') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_encryption')" name="encryption" type="text" placeholder="tls or ssl" label="{{ __('Mail Encryption') }}" />
                                </div>
                                <div class="col-lg-6">
                                    <x-input :value="config('app.mail_from_address')" name="from_address" type="text" placeholder="from email address" label="{{ __('Mail From Address') }}" required />
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary py-2">{{ __('Save And Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .infoBtn {
            border: none;
            width: 20px;
            height: 20px;
            border-radius: 100%;
            font-size: 12px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
    </style>
@endsection
