@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4>
            {{ __('Sub Categories') }}
        </h4>

        <a href="{{ route('shop.subcategory.create') }}" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{__('Create New')}}
        </a>

    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="cardTitleBox">
                    <h5 class="card-title chartTitle">
                        {{__('Sub Categories')}}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($subCategories as $key => $subCategory)
                            @php
                                $serial = $subCategories->firstItem() + $key;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $serial }}</td>

                                <td>
                                    <img src="{{ $subCategory->thumbnail }}" width="50">
                                </td>
                                <td>
                                    @forelse ($subCategory->categories  as $category)
                                        <span class="badge rounded-pill text-bg-primary me-1">{{ $category->name }}</span>
                                    @empty
                                        N/A
                                    @endforelse
                                </td>

                                <td>{{ $subCategory->name }}</td>

                                <td>
                                    <label class="switch mb-0">
                                        <a href="{{ route('shop.subcategory.toggle', $subCategory->id) }}">
                                            <input type="checkbox" {{ $subCategory->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-3 justify-content-center">

                                        <a href="{{ route('shop.subcategory.edit', $subCategory->id) }}" class="btn btn-outline-primary circleIcon">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

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
            {{ $subCategories->withQueryString()->links() }}
        </div>

    </div>
@endsection
