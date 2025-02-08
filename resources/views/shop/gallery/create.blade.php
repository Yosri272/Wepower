@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">

                <div class="card-header">
                    <h4>
                        {{ __('Upload Zip File') }}
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('shop.gallery.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @if (session('total') && session('folder_name'))
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">
                                    {{ __('Well done!') }}
                                </h4>
                                <p><strong>{{ session('total') }}</strong> {{ __('files uploaded to') }}</p>
                                    <strong>{{ session('folder_name') }} {{ __('Folder') }}</strong>
                                </p>
                                <hr>
                                <p class="mb-0">
                                    <a href="{{ route('shop.gallery.index') }}" class="btn btn-primary">
                                        {{ __('View Gallery') }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="formFile" class="form-label">
                                {{ __('Choose zip file') }}
                            </label>
                            <div class="drop-zone mx-auto">
                                <span class="drop-zone__prompt">
                                    <div class="icon">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    {{ __('Drop file here or click to upload') }}
                                </span>
                                <input name="zip_file" type="file" id="formFile" class="drop-zone__input"
                                    accept=".zip">
                            </div>
                            @error('zip_file')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary py-2 px-3">
                            {{ __('Upload File') }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/scripts/drop-zone.js') }}"></script>
@endpush
