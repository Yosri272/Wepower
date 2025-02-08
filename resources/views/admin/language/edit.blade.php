@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <form action="{{ route('admin.language.update', $language->id) }}" method="POST" class="mb-5">
            @csrf @method('put')
            <div class="card border-0 shadow-sm rounded-12">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h3 class="m-0">{{ __('Edit Language') }}</h3>
                    <a href="{{ route('admin.language.index') }}" class="btn btn-danger">
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <x-input type="text" name="title" :value="$language->title" label="Title" />
                        <input type="hidden" name="name" value="{{ $language->name }}" />
                    </div>
                    <table class="table table-bordered">
                        <tr class="table-light">
                            <th>{{ __('Key') }}</th>
                            <th>{{ __('Value') }}</th>
                        </tr>
                        <tbody>
                            @foreach ($languageData as $key => $value)
                                <tr>
                                    <th style="max-width: 320px;">
                                        {{ $key }}
                                        <input type="hidden" name="data[{{ $loop->index }}][key]" value="{{ $key }}">
                                    </th>
                                    <td class="py-2">
                                        <textarea name="data[{{ $loop->index }}][value]" class="languageInput" rows="2" placeholder="value for {{ $language->name }}">{{ $value ?? '' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="submitBtn">
                <button type="submit" class="btn btn-primary">{{ __('Save And Update') }}</button>
            </div>
        </form>
    </div>
    <style>
        .submitBtn {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
            border-top: 1px solid #eceff1;
        }
    </style>
@endsection
