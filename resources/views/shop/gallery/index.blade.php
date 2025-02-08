@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Gallery Images') }}
        </h4>
        <a href="{{ route('shop.gallery.create') }}" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{__('Upload Images')}}
        </a>
    </div>

    @php
        $hasFolder = request('folder');
    @endphp

    <div class="container-fluid mt-3">

        <div class="card my-3">
            <div class="card-body">

                <div class="d-flex gap-2 pb-2">
                    <h5>
                        <i class="fa-solid fa-images"></i>
                       {{ __('Gallery') }}
                        @if ($hasFolder)
                            ({{ $hasFolder }} {{__('files')}})
                        @else
                            {{__('folders')}}
                        @endif
                    </h5>
                </div>

                @if (!$hasFolder)
                    <div class="d-flex flex-wrap gap-3">
                        @forelse($galleries as $gallery)
                            <a href="{{ route('shop.gallery.index', 'folder=' . $gallery->name) }}" class="galleryFolder text-center">
                                <div class="icon fs-1 mx-auto">
                                    <i class="fa-solid fa-folder"></i>
                                </div>
                                {{ $gallery->name }}
                            </a>
                        @empty
                            <p>{{__('No gallery found!')}}</p>
                        @endforelse
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-3">
                        @forelse($folderFiles as $file)
                            @php
                                $reaPath = realpath($file);

                                $fileName = pathinfo($reaPath, PATHINFO_FILENAME);

                                $extension = pathinfo($file, PATHINFO_EXTENSION);
                                $file = $fileName . '.' . $extension;
                                $folderName = 'gallery/shop' . auth()->user()->shop->id . '/' . $hasFolder;
                                $url = Storage::url($folderName . '/' . $file);
                            @endphp

                            <div class="galleryItem" title="{{ $file }}">
                                <img src="{{ $url }}" alt="imageI" loading="lazy" height="100" />
                                <p class="d-block">
                                    <span class="name">{{ $fileName }}</span><span>.{{ $extension }}</span>
                                </p>
                            </div>
                        @empty
                            <p>{{__('Folder is empty!')}}</p>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
        @if (!$hasFolder)
            <div>
                {{ $galleries->links() }}
            </div>
        @endif
    </div>

    <style>
        .galleryFolder{
            padding: 8px 16px;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: #000;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .galleryFolder:hover{
            background-color: #e0e0e0;
            color: var(--theme-color);
        }
        .galleryItem {
            width: 100px;
            overflow: hidden;
        }

        .galleryItem img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .galleryItem p {
            margin: 0;
            overflow: hidden;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .galleryItem .name {
            max-width: 85px;
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
