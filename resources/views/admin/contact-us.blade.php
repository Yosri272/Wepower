@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-8 col-lg-9 m-auto">
                <form action="{{ route('admin.contactUs.update', $contactUs?->id) }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header py-3">
                            <h4 class="m-0">{{ __('Contact Us') }}</h4>
                        </div>
                        <div class="card-body pb-4">
                            <div class="mb-3">
                                <x-input type="text" name="phone" label="Phone Number" placeholder="Phone Number" :value="$contactUs?->phone"/>
                            </div>

                            <div class="mb-4">
                                <x-input type="text" name="whatsapp" label="Whatsapp Number" placeholder="Whatsapp Number" :value="$contactUs?->whatsapp"/>
                            </div>

                            <div class="mb-4">
                                <x-input type="text" name="messenger" label="Messenger Link" placeholder="Messenger link" :value="$contactUs?->messenger"/>
                            </div>

                            <div class="">
                                <x-input type="text" name="email" label="Email Address" placeholder="Email Address" :value="$contactUs?->email"/>
                            </div>
                        </div>
                        <div class="card-footer py-3 ">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary py-2">{{ __('Save And Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

