@extends('layouts.app')

@section('content')
    <div class="container-fluid mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h4 class="m-0">{{ __('Edit') }} {{ __($page->title) }}</h4>

            <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">
                <i class="fa fa-arrow-left"></i>
                {{ __('Back') }}
            </a>
        </div>

        <form action="{{ route('admin.legalpage.update', $page?->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 rounded-12">
                <div class="card-body">
                    <div>
                        <x-input name='title' type="text" placeholder="Title" value="{{ $page->title }}"
                            label="Title" />
                    </div>

                    <div class="mt-3">
                        <label for="editor" class="fw-bold">{{ __('Content') }}</label>

                        <div id="editor">
                            {!! old('description') ?? $page->description !!}
                        </div>
                        <input type="hidden" id="description" name="description" value="{{ old('description') ?? $page->description }}">
                        @error('description')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror
                        @error('description')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-primary px-4 py-2" type="submit">{{ __('Save And Update') }}</button>
                </div>
            </div>
        </form>

    </div>
@endsection
@push('scripts')
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    [{
                        'font': []
                    }],
                    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['link', 'image', 'video', 'formula']
                ]
            }
        });

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('description').value = quill.root.innerHTML;
        });
    </script>
@endpush
