@extends('layouts.app')
@section('content')
    <div>
        <h4>
            {{ __('Shop Details') }}
        </h4>
    </div>
     @include('admin.shop.header-nav')

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">SL.</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th class="text-center">{{ __('Total Products') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td>
                                    <img src="{{ $category->thumbnail }}" width="50">
                                </td>

                                <td>{{ $category->name }}</td>

                                <td class="text-center">{{ $category->products->count() }}</td>

                                <td class="text-center">
                                    <label class="switch mb-0">
                                        <a href="{{ route('admin.category.status.toggle', $category->id) }}">
                                            <input type="checkbox" {{ $category->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $categories->links() }}
        </div>

    </div>
@endsection
