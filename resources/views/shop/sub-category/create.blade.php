@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-border-all"></i> {{__('Create New Sub Category')}}
        </div>
    </div>
    <form action="{{ route('shop.subcategory.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card mt-3">
                    <div class="card-body">

                        <div class="d-flex gap-2 border-bottom pb-2">
                            <i class="fa-solid fa-user"></i>
                            <h5>
                                {{ __('Sub Category Information') }}
                            </h5>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">
                                {{ __('Select Category') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select name="category[]" data-placeholder="Select Category" class="form-control select2"
                                multiple style="width: 100%">
                                <option value="" disabled>
                                    {{ __('Select Category') }}
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <x-input label="Name" name="name" type="text" placeholder="Name" />
                        </div>

                        <div class="mt-3 d-flex align-items-center justify-content-center">
                            <div class="ratio1x1">
                                <img id="previewProfile" src="https://placehold.co/500x500/f1f5f9/png" alt=""
                                    width="100%">
                            </div>
                        </div>
                        <div class="mt-3">
                            <x-file name="thumbnail" label="Thumbnail (Ratio 1:1) *" preview="previewProfile" />
                        </div>

                        <div class="mt-5 d-flex gap-2 justify-content-between">
                            <a href="{{ route('shop.category.index') }}" class="btn btn-secondary py-2 px-4">
                                {{__('Back')}}
                            </a>

                            <button type="submit" class="btn btn-primary py-2 px-4">
                                {{__('Submit')}}
                            </button>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </form>
@endsection
