@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4>
            {{__('Brand List')}}
        </h4>

        <button type="button" data-bs-toggle="modal" data-bs-target="#createBrand" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{__('Create New')}}
        </button>

    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="cardTitleBox">
                    <h5 class="card-title chartTitle">
                        {{__('Brands')}}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table border table-responsive-md">
                        <thead>
                            <tr>
                                <th class="text-center">{{_('SL') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($brands as $key => $brand)
                            @php
                                $serial = $brands->firstItem() + $key;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $serial }}</td>
                                <td>{{ $brand->name }}</td>

                                <td>
                                    <label class="switch mb-0">
                                        <a href="{{ route('shop.brand.toggle', $brand->id) }}">
                                            <input type="checkbox" {{ $brand->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-3 justify-content-center">
                                        <button type="button" class="btn btn-outline-primary btn-sm circleIcon" onclick="openUpdateModal({{ $brand }})">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $brands->withQueryString()->links() }}
        </div>

    </div>


    <!--=== Create Brand Modal ===-->
    <form action="{{ route('shop.brand.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="createBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Create Brand') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="name" class="form-label">{{__('Name')}} *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter Name" required />
                            @error('name')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--=== Edit Brand Modal ===-->
    <form action="" id="formEditBrand" method="POST">
        @csrf
        @method('PUT')
        <div class="modal fade" id="updateBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{__('Edit Brand')}}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align: left">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{__('Name')}} *</label>
                            <input type="text" class="form-control" id="editName" name="name"
                                placeholder="Enter Name" value="" required />
                            @error('name')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        const openUpdateModal = (brand) => {
            $("#editName").val(brand.name);
            $("#formEditBrand").attr('action', `{{ route('shop.brand.update', ':id') }}`.replace(':id', brand.id));

            $("#updateBrand").modal('show');
        }
    </script>
@endpush
