@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-12 mt-2 mx-auto ">
                <div class="card shadow">
                    <div class="card-header bg-custom py-2">
                        <h3 class="m-0">{{ __('SMS Configuration') }}</h3>
                        <span class="badge badge-info">You can active only one provider at a time</span>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            {{-- Twilio Config Start --}}
                            <div class="col-md-6 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white">
                                        <img src="{{ asset('assets/sms-gateway/Twilio.png') }}" alt="Twilio"
                                            height="70px">
                                    </div>
                                    <form action="{{ route('admin.sms-gateway.update') }}" method="POST">
                                        <div class="card-body">
                                            @method('put')
                                            @csrf
                                            <div class="row">
                                                <input type ="hidden" name="provider" value="twilio">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $twilio?->status == 1 ? 'checked' : '' }} value="1"
                                                            type="radio" name="status" id="twilioActive">
                                                        <label class="form-check-label" for="twilioActive">
                                                            Active
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $twilio?->status == 0 ? 'checked' : '' }} value="0"
                                                            type="radio" name="status" id="twilioInactive">
                                                        <label class="form-check-label" for="twilioInactive">
                                                            Inactive
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    @php
                                                        $twilio = json_decode($twilio?->data);
                                                    @endphp
                                                    <x-input value="{{ $twilio?->twilio_sid }}" name="twilio_sid"
                                                        type="text" placeholder="Twilio SID" />
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <x-input value="{{ $twilio?->twilio_token }}" name="twilio_token"
                                                        type="text" placeholder="Twilio Token" />
                                                </div>
                                                <div class="col-md-12">
                                                    <x-input value="{{ $twilio?->twilio_from }}" name="twilio_from"
                                                        type="text" placeholder="Twilio From" />
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-footer bg-white">
                                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- Twilio Config End --}}

                            {{-- Telesign Config Start --}}
                            <div class="col-md-6 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white">
                                        <img src="{{ asset('assets/sms-gateway/Telesign.png') }}" alt="Telesign"
                                            height="70px">
                                    </div>
                                    <form action="{{ route('admin.sms-gateway.update') }}" method="POST">
                                        <div class="card-body">
                                            @method('put')
                                            @csrf
                                            <div class="row">
                                                <input type ="hidden" name="provider" value="telesign">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $telesign?->status == 1 ? 'checked' : '' }} value="1"
                                                            type="radio" name="status" id="teleSignActive">
                                                        <label class="form-check-label" for="teleSignActive">
                                                            Active
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $telesign?->status == 0 ? 'checked' : '' }} value="0"
                                                            type="radio" name="status" id="teleSignInactive">
                                                        <label class="form-check-label" for="teleSignInactive">
                                                            Inactive
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    @php
                                                        $telesign = json_decode($telesign?->data);
                                                    @endphp
                                                    <x-input value="{{ $telesign?->customer_id }}" name="customer_id"
                                                        type="text" placeholder="Customer ID" />
                                                </div>
                                                <div class="col-md-12">
                                                    <x-input value="{{ $telesign?->api_key }}" name="api_key"
                                                        type="text" placeholder="API KEY" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- Telesign Config End --}}

                            {{-- Nexmo Config Start --}}
                            <div class="col-md-6 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white">
                                        <img src="{{ asset('assets/sms-gateway/Nexmo.png') }}" alt="Nexmo"
                                            height="70px">
                                    </div>
                                    <form action="{{ route('admin.sms-gateway.update') }}" method="POST">
                                        <div class="card-body">
                                            @method('put')
                                            @csrf
                                            <div class="row">
                                                <input type ="hidden" name="provider" value="nexmo">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $nexmo?->status == 1 ? 'checked' : '' }} value="1"
                                                            type="radio" name="status" id="nexmoActive">
                                                        <label class="form-check-label" for="nexmoActive">
                                                            Active
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $nexmo?->status == 0 ? 'checked' : '' }} value="0"
                                                            type="radio" name="status" id="nexmoInactive">
                                                        <label class="form-check-label" for="nexmoInactive">
                                                            Inactive
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    @php
                                                        $nexmo = json_decode($nexmo?->data);
                                                    @endphp
                                                    <x-input value="{{ $nexmo?->nexmo_key }}" name="nexmo_key"
                                                        type="text" placeholder="Nexmo Key" />
                                                </div>
                                                <div class="col-md-12">
                                                    <x-input value="{{ $nexmo?->nexmo_secret }}" name="nexmo_secret"
                                                        type="text" placeholder="Nexmo Secret" />
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <x-input value="{{ $nexmo?->nexmo_from ?? '' }}" name="nexmo_from"
                                                        type="text" placeholder="Nexmo From" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- Nexmo Config End --}}

                            {{-- Message Bird Config Start --}}
                            <div class="col-md-6 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white">
                                        <img src="{{ asset('assets/sms-gateway/MessageBird.png') }}" alt="MessageBird"
                                            height="70px">
                                    </div>
                                    <form action="{{ route('admin.sms-gateway.update') }}" method="POST">
                                        <div class="card-body">
                                            @method('put')
                                            @csrf
                                            <div class="row">
                                                <input type ="hidden" name="provider" value="message_bird">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $messageBird?->status == 1 ? 'checked' : '' }}
                                                            value="1" type="radio" name="status"
                                                            id="messageBirdActive">
                                                        <label class="form-check-label" for="messageBirdActive">
                                                            Active
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            {{ $messageBird?->status == 0 ? 'checked' : '' }}
                                                            value="0" type="radio" name="status"
                                                            id="messageBirdInactive">
                                                        <label class="form-check-label" for="messageBirdInactive">
                                                            Inactive
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    @php
                                                        $messageBird = json_decode($messageBird?->data);
                                                    @endphp
                                                    <x-input value="{{ $messageBird?->api_key }}" name="api_key"
                                                        type="text" placeholder="API Key" />
                                                </div>
                                                <div class="col-md-12">
                                                    <x-input value="{{ $messageBird?->from }}" name="from"
                                                        type="text" placeholder="From" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- Message Bird Config End --}}
                        </div>
                    </div>
                </div>
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
