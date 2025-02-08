@extends('layouts.app')

@section('content')
    <div class="container-fluid mb-3">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

            <h4 class="m-0">{{ __($page->title) }}</h4>

            <a href="{{ route('admin.legalpage.edit', $page->slug) }}" class="btn btn-sm btn-primary px-3 py-2">
                <i class="fa-solid fa-pen me-1"></i> {{ __('Edit') }}
            </a>
        </div>

        <div class="card rounded-12 border-0">
            <div class="card-header py-3">
                <h5 class="m-0">{{ __('Content') }}</h5>
            </div>
            <div class="card-body">
                {!! $page->description !!}
            </div>
        </div>

    </div>
@endsection
