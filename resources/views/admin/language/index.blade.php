@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-8 col-lg-9 mt-2 mx-auto ">
                <div class="card border-0 rounded shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0">{{ __('Languages') }}</h3>
                        <a class="btn btn-primary" href="{{ route('admin.language.create') }}">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            {{ __('Create New') }}
                        </a>
                    </div>
                </div>

                @foreach ($languages as $language)
                    <div class="language-item shadow-sm">
                        <div class="d-flex gap-2 flex-wrap">
                            <div style="min-width: 160px">
                                <small class="text-black-50 d-block fst-italic" style="line-height: 0.7;">
                                    {{ __('Title') }}
                                </small>
                                <strong class="fs-6">{{ $language->title }}</strong>
                            </div>

                            <div>
                                <small class="text-black-50 d-block fst-italic" style="line-height: 0.7;">
                                    {{ __('Name') }}
                                </small>
                                <strong>{{ $language->name }}</strong>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.language.edit', $language->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>
                            @if ($language->name != 'en')
                                <a class="delete-confirm btn btn-danger btn-sm"
                                    href="{{ route('admin.language.delete', $language->id) }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: "{{__('Are you sure?')}}",
                text: "{{__('You will not be able to revert this!')}}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00B894',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, delete it!')}}",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        });
    </script>
@endpush
