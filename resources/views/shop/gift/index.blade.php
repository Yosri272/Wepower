@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4>
            {{ __('Gift List') }}
        </h4>

        <a href="{{ route('shop.gift.create') }}" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{__('Create New')}}
        </a>

    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="cardTitleBox">
                    <h5 class="card-title chartTitle">
                        {{__('All Gifts')}}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($gifts as $key => $gift)
                            @php
                                $serial = $gifts->firstItem() + $key;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $serial }}</td>

                                <td>
                                    <img src="{{ $gift->thumbnail }}" width="50">
                                </td>

                                <td>{{ $gift->name }}</td>
                                <td>{{ showCurrency($gift->price) }}</td>

                                <td>
                                    <label class="switch mb-0">
                                        <a href="{{ route('shop.gift.toggle', $gift->id) }}">
                                            <input type="checkbox" {{ $gift->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-3 justify-content-center">

                                        <a href="{{ route('shop.gift.edit', $gift->id) }}" class="btn btn-outline-primary circleIcon">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <a href="{{ route('shop.gift.destroy', $gift->id) }}" class="btn btn-outline-danger circleIcon deleteConfirm">
                                            <i class="bi bi-trash"></i>
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
            {{ $gifts->withQueryString()->links() }}
        </div>

    </div>
@endsection
