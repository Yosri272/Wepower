@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-image"></i> {{__('Edit Ad')}}
        </div>
    </div>
    <form action="{{ route('admin.ad.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">

            <div class="col-md-6">
                <div class="card mt-3 h-100">
                    <div class="card-body">
                        <div class="">
                            <x-input label="Title" name="title" type="text" placeholder="Enter Short Title" :value="$ad->title" />
                        </div>

                        <div class="mt-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="ratio3x2">
                                    <img src="{{ $ad->thumbnail ?? asset('defualt/defualt.jpg') }}" id="banner" alt="" width="100%">
                                </div>
                            </div>
                            <x-file name="banner" label="Ad Ratio (400 x 250 px) *" preview="banner" />
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button class="btn btn-primary py-2 px-5">
                                {{__('Submit')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection
