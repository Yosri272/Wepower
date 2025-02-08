@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Bulk Product Imports') }}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        {{-- <div class="card my-3">
            <div class="card-body">

                <div class="d-flex gap-2 pb-2">
                    <h5>
                        <i class="fa-solid fa-file-import"></i>
                        Export CSV Template
                    </h5>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#exampleModal"
                        class="btn py-2 btn-primary d-inline-flex gap-2 align-items-center">
                        <i class="fa-solid fa-file-csv fs-3"></i>
                        Make CSV Template
                    </button>

                </div>

            </div>
        </div> --}}

        @if (session('total'))
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">{{ __('Well done!') }}</h4>
                <p><strong>{{ session('total') }}</strong>
                    {{ __('total products imported successfully') }}
                </p>
                <hr>
                <p class="mb-0">
                    <a href="{{ route('shop.product.index') }}" class="btn btn-primary">
                        {{ __('View Products') }}
                    </a>
                </p>
            </div>
        @endif

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#showInstraction" aria-expanded="false" aria-controls="showInstraction">
                        <span class="info me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="{{ __('Get instructions for bulk import') }}">
                            <i class="bi bi-info"></i>
                        </span>
                        {{ __('Get instructions') }}
                    </button>
                </h2>
                <div id="showInstraction" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">

                        <div class="row g-4">
                            <!-- Step 1 -->
                            <div class="col-lg-6 col-xl-4">
                                <div class="export-steps-item h-100">
                                    <div class="d-flex gap-3 justify-content-between align-items-center">
                                        <div>
                                            <h3 class="fz-20 text-dark">
                                                {{ __('Step 1') }}
                                            </h3>
                                            <div>
                                                {{ __('Download Excel File') }}
                                            </div>
                                        </div>
                                        <img src="{{ asset('assets/images/bulk-import-1.png') }}" alt="">
                                    </div>

                                    <h4 class="mt-3 text-dark fz-20">
                                        {{ __('Instruction') }}
                                    </h4>

                                    <ul class="m-0 pl-4">
                                        <li>
                                            {{ __('Please download the format file and fill it with the appropriate data.') }}
                                        </li>
                                        <li>
                                            {{ __('To understand how to fill the data correctly, you can download the example file as a guide.') }}
                                        </li>
                                        <li>
                                            {{ __('You need to upload the Excel file.') }}
                                        </li>
                                    </ul>

                                    <div class="mt-4">
                                        <a href="{{ route('shop.bulk-product-export.demo') }}"
                                            class="btn btn-primary py-2">
                                            <i class="fa-solid fa-download"></i>
                                            {{ __('Download Template') }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="col-lg-6 col-xl-4">
                                <div class="export-steps-item h-100">
                                    <div class="d-flex gap-3 justify-content-between align-items-center">
                                        <div>
                                            <h3 class="fz-20 text-dark">
                                                {{ __('Step 2') }}
                                            </h3>
                                            <div>
                                                {{ __('Match Spread sheet data according to instruction') }}
                                            </div>
                                        </div>
                                        <img src="{{ asset('assets/images/bulk-import-2.png') }}" alt="">
                                    </div>

                                    <h4 class="mt-3 text-dark fz-20">
                                        {{ __('Instruction') }}
                                    </h4>

                                    <ul class="m-0 pl-4">
                                        <li>
                                            {{ __('Fill in the data according to the format.') }}
                                        </li>
                                        <li>
                                            {{ __('Ensure the thumbnail image is properly uploaded and the image name follows the correct format. The accepted image formats are jpg, jpeg, png, and gif.') }}
                                        </li>
                                        <li>
                                            {{ __('Adding a category is required and category is one. make sure the category name is correct.') }}
                                        </li>
                                        <li>
                                            {{ __('You have the option to add multiple sub categories. Make sure each sub category name is accurate and separate the names with commas.') }}
                                        </li>
                                        <li>
                                            {{ __('Adding a brand to the product entry is optional. If you include one, ensure you enter a single brand name accurately. The brand name must be correct to maintain accuracy in the data entry process.') }}
                                        </li>
                                        <li>
                                            {{ __('You can add multiple colours. Ensure each colour name is correct and separate them with commas.') }}
                                        </li>
                                        <li>
                                            {{ __('You can add multiple sizes. Ensure each size name is correct and separate them with commas.') }}
                                        </li>
                                        <li>
                                            {{ __('Price is required and must be a number.') }}
                                        </li>
                                        <li>
                                            {{ __('Discount price is optional and must be less than the original price.') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="col-lg-6 col-xl-4">
                                <div class="export-steps-item h-100">
                                    <div class="d-flex gap-3 justify-content-between align-items-center">
                                        <div>
                                            <h3 class="fz-20 text-dark">
                                                {{ __('Step 3') }}
                                            </h3>
                                            <div>
                                                {{ __('Validate data and complete import') }}
                                            </div>
                                        </div>
                                        <img src="{{ asset('assets/images/bulk-import-3.png') }}" alt="">
                                    </div>

                                    <h4 class="mt-3 text-dark fz-20">
                                        {{ __('Instruction') }}
                                    </h4>

                                    <ul class="m-0 pl-4">
                                        <li>
                                            {{ __('In the Excel file upload section first select the upload option.') }}
                                        </li>
                                        <li>
                                            {{ __('Upload your file in .xlsx format.') }}
                                        </li>
                                        <li>
                                            {{ __("If you have thumbnails, click the 'Select Gallery Folder' button. Next, choose the folder containing product thumbnails from the Excel file you have selected. Finally, click the 'Confirm and Import' button.") }}
                                        </li>
                                        <li>
                                            {{ __("If you do not have thumbnails, click on the 'Import Without Gallery' button.") }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="card my-3">
            <div class="card-body text-center">
                <h4 class="text-muted mb-3">
                    {{ __('Select Excle(xlsx) File to Import') }}
                </h4>
                <form action="{{ route('shop.bulk-product-import.store') }}" method="POST" enctype="multipart/form-data"
                    id="bulkForm">
                    @csrf

                    <div class="drop-zone mx-auto">
                        <span class="drop-zone__prompt">
                            <div class="icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            {{ __('Drop file here or click to upload') }}
                        </span>
                        <input name="file" type="file" class="drop-zone__input" accept=".xlsx">
                    </div>
                    @error('file')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror

                    <div id="galler" style="display: none">
                        <button type="submit" class="btn btn-primary btn-lg mt-3 py-2">
                            {{ __('Import Without Gallery') }}
                        </button>

                        <button type="button" class="btn btn-outline-primary btn-lg mt-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#galleryModal">
                            {{ __('Select Gallery Folder') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="galleryModalLabel">
                        {{ __('Select Gallery Folder') }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <ul class="mb-2">
                        <li class="fw-medium">
                            {{ __('Select Gallery Folder there has product thumbnails in selected excle file') }}
                        </li>
                    </ul>

                    <div class="d-flex gap-3 flex-wrap mt-3">

                        @foreach ($galleries as $gallery)
                            <button type="button" onclick="selectFolder(this, '{{ $gallery->name }}')"
                                class="btn border galleryFolder">
                                <div class="icon fs-3">
                                    <i class="fa-solid fa-folder"></i>
                                </div>
                                {{ $gallery->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary" onclick="submitForm()">
                        {{ __('Confirm And Import') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <form action="{{ route('shop.bulk-product-import.formatExport') }}" method="GET">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            {{ __('Generate CSV Template') }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="">
                            {{ __('how many products you want to import?') }}
                        </label>
                        <input type="number" class="form-control" name="quantity" value="" required
                            placeholder="Enter quantity" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('css')
    <style>
        .galleryFolder {
            color: #000;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .galleryFolder:hover {
            color: var(--theme-color);
        }

        .galleryFolder.active {
            color: var(--theme-color);
            border: 2px solid var(--theme-color) !important;
        }

        .export-steps-item ul li {
            margin-bottom: 6px;
        }

        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/scripts/drop-zone.js') }}"></script>
    <script>
        $('input[name="file"]').change(function() {
            $('#galler').css('display', 'block');
        });

        function selectFolder(button, name) {
            var gallery = $('#bulkForm');

            var input = $('#input' + name);

            if (input.length) {
                input.remove();
                $(button).removeClass('active');
            } else {
                var element = document.createElement('input');
                element.type = 'hidden';
                element.name = 'folder[' + name + ']';
                element.value = name;
                element.id = 'input' + name;
                gallery.append(element);

                $(button).addClass('active');
            }
        }

        function submitForm() {
            $('#galleryModal').modal('hide');

            $('#bulkForm').submit();
        }
    </script>
@endpush
