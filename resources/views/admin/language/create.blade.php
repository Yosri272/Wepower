@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-7 mt-2 mx-auto ">
                <form action="{{ route('admin.language.store') }}" method="POST">
                    @csrf
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h3 class="m-0">{{ __('Create New Language') }}</h3>
                        </div>
                        <div class="card-body">
                            <div>
                                <x-input type="text" name="title" label="Title" placeholder="Title" value="" />
                            </div>
                            <div class="mt-3">
                                <label class="mb-0">
                                    {{ __('Short Name') }} <small>
                                        ({{ __('only allow English characters') }})</small>
                                </label>
                                <input name="name" oninput="this.value=this.value.replace(/[^a-z]/gi,'')"
                                    class="form-control" placeholder="{{ __('Exm: bn') }}" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between flex-wrap gap-2 ">
                            <a href="{{ route('admin.language.index') }}" class="btn btn-danger">
                                {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary py-2 px-4">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
