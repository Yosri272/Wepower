@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <div class="d-flex align-items-center flex-wrap gap-1">
            <h4>{{ __('Ads List') }}</h4>
            <span>({{__('max 2 ads show in home page')}})</span>
        </div>

        <a href="{{ route('admin.ad.create') }}" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{__('Create New')}}
        </a>
    </div>

    <div class="container-fluid mt-3">

        <div class="my-3 card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border table-responsive-lg">
                        <thead>
                            <tr>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($ads as $ad)
                            <tr>
                                <td>
                                    <img src="{{ $ad->thumbnail }}" height="76">
                                </td>

                                <td>{{ $ad->title }}</td>

                                <td class="text-center">
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Status Toggle">
                                        <a href="{{ route('admin.ad.toggle', $ad->id) }}">
                                            <input type="checkbox" {{ $ad->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">

                                        <a href="{{ route('admin.ad.edit', $ad->id) }}" class="btn btn-outline-info btn-sm circleIcon">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <a href="{{ route('admin.ad.destroy', $ad->id) }}" class="btn btn-outline-danger btn-sm deleteConfirm circleIcon">
                                            <i class="fa-solid fa-trash"></i>
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
            {{ $ads->links() }}
        </div>

    </div>
@endsection

