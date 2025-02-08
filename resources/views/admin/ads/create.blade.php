@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-image"></i> {{__('Add New Ad')}}
        </div>
    </div>
    <form action="{{ route('admin.ad.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="card mt-3 h-100">
                    <div class="card-body">
                        <div class="">
                            <x-input label="Title" name="title" type="text" placeholder="Enter Short Title" />
                        </div>

                        <div class="mt-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="ratio3x2">
                                    <img src="https://placehold.co/400x250/f1f5f9/png" id="banner" alt="" width="100%">
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
