@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-8 col-lg-9 m-auto">
                <form action="{{ route('admin.pusher.update') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header py-3">
                            <h4 class="m-0">{{ __('Pusher Configuration') }}</h4>
                        </div>
                        <div class="card-body pb-4">
                            <div class="mb-3">
                                <x-input type="text" name="app_id" label="PUSHER APP ID" placeholder="PUSHER APP ID" :value="config('broadcasting.connections.pusher.app_id')"/>
                            </div>

                            <div class="mb-4">
                                <x-input type="text" name="app_key" label="PUSHER APP KEY" placeholder="PUSHER APP KEY" :value="config('broadcasting.connections.pusher.key')"/>
                            </div>

                            <div class="">
                                <x-input type="text" name="app_secret" label="PUSHER APP SECRET" placeholder="PUSHER APP SECRET" :value="config('broadcasting.connections.pusher.secret')"/>
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

