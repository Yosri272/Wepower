@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-gear-fill"></i> {{ __('Theme Colors Settings') }}
        </div>
    </div>
    <form action="{{ route('admin.themeColor.update') }}" method="POST">
        @csrf
        <div class="card mt-3">
            <div class="card-header py-3">
                <h5 class="m-0 card-title">
                    <i class="bi bi-palette-fill"></i>
                    {{ __('Current Color') }}
                </h5>
            </div>
            <div class="card-body">

                <div class="d-flex align-items-center gap-5 mt-4 flex-wrap">
                    <div class="color-panel">
                        <span class="color-input" id="bgPrimary" style="background: {{ $primary }}"></span>
                        <p class="color" id="colorPrimary">
                            {{ $primary }}
                        </p>
                        <label for="primary_color" class="color-label">
                            {{__('Primary')}}
                        </label>
                    </div>

                    <div class="color-panel">
                        <span class="color-input" id="bgSecondary" style="background: {{ $secondary }}"></span>
                        <p class="color" id="colorSecondary">
                            {{ $secondary }}
                        </p>
                        <label for="secondary_color" class="color-label">
                            {{__('Secondary') }}
                        </label>
                    </div>
                </div>

                <input type="hidden" name="primary_color" value="{{ $primary }}" id="primary_color" />
                <input type="hidden" name="secondary_color" value="{{ $secondary }}" id="secondary_color" />

            </div>
            <div class="card-footer d-flex justify-content-start">
                <button type="submit" class="btn btn-primary py-2 px-3">
                    {{__('Save And Update')}}
                </button>
            </div>
        </div>
    </form>

    <div class="mt-4">
        <h5 class="fw-bold">
            {{__('Available Colors palette')}}
        </h5>

        <div class="d-flex gap-3 flex-wrap">
            @foreach ($themeColors as $color => $colors)
                @php
                    $colorNames = config('themeColors.colors');
                    $colorName = array_search($color, $colorNames);
                @endphp

                <div class="color-panel">
                    <button class="primary-color" style="background: {{ $color }};"
                        onclick="setColor('{{ $color }}', '{{ $colors['100'] }}')">
                        <div class="name">{{ $colorName }}</div>
                        <span class="color">{{ $color }}</span>
                    </button>
                    <div class="variants">
                        @foreach ($colors as $key => $value)
                            <div class="varint-color {{ $loop->index > 4 ? 'text-white' : '' }}"
                                style="background: {{ $value }}">
                                {{ $colorName . '-' . $key }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
@push('css')
    <style>
        .color-panel {
            width: 210px;
        }

        .color-panel .primary-color {
            width: 100%;
            border: none;
            padding: 10px;
            cursor: pointer;
            text-transform: capitalize;
            text-align: left;
            color: #fff;
            border: 2px dotted transparent;
            transition: all 0.3s;
        }

        .color-panel .primary-color:hover {
            border: 2px dotted #ccc;
            box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
        }

        .color-panel .primary-color .name {
            font-size: 16px;
            font-weight: 700;
        }

        .color-panel .variants {
            width: 100%;
            cursor: not-allowed;
        }

        .color-panel .varint-color {
            padding: 5px 10px;
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function setColor(primary, secondary) {
            document.getElementById('primary_color').value = primary;
            document.getElementById('secondary_color').value = secondary;
            document.getElementById('bgPrimary').style.background = primary;
            document.getElementById('bgSecondary').style.background = secondary;
            document.getElementById('colorPrimary').innerHTML = primary;
            document.getElementById('colorSecondary').innerHTML = secondary;
        }
    </script>
@endpush
