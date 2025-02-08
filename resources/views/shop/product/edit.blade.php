@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-shop"></i> {{__('Edit Product')}}
        </div>
    </div>
    <form action="{{ route('shop.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mt-3">
            <div class="card-body">

                <div class="">
                    <x-input label="Product Name" name="name" type="text" placeholder="Product Name"
                        required="true" value="{{ $product->name }}" />
                </div>

                <div class="mt-3">
                    <label for="">
                        {{ __('Short Description') }}
                        <span class="text-danger">*</span>
                    </label>
                    <textarea name="short_description" class="form-control" rows="2" placeholder="Short Description">{{ old('short_description') ?? $product->short_description }}</textarea>
                </div>

                <div class="mt-3">
                    <label for="">
                        {{ __('Description') }}
                    </label>
                    <div id="editor">
                        {!! old('description') ?? $product->description !!}
                    </div>
                    <input type="hidden" id="description" name="description"
                        value="{{ old('description') ?? $product->description }}">
                    @error('description')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!--######## General Information ##########-->
        <div class="card mt-4">
            <div class="card-body">

                <div class="d-flex gap-2 border-bottom pb-2">
                    <i class="fa-solid fa-user"></i>
                    <h5>
                        {{__('Generale Information')}}
                    </h5>
                </div>

                <div class="row mt-3">

                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">
                            {{ __('Select Category') }}
                            <span class="text-danger">*</span>
                        </label>
                        <select name="category" class="form-control select2"
                            style="width: 100%">
                            <option value="" disabled>
                                {{ __('Select Category') }}
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ in_array($category->id, $product->categories?->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4 mt-3 mt-md-0">
                        <label class="form-label">
                            {{ __('Select Sub Categories') }}
                            <span class="text-danger">*</span>
                        </label>
                        <select name="sub_category[]" class="form-control select2" multiple style="width: 100%">
                            <option value="" disabled>
                                {{ __('Select Sub Category') }}
                            </option>
                            @foreach ($subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}"
                                    {{ in_array($subCategory->id, $product->subcategories?->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $subCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_category')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4 mt-3 mt-md-0">
                        <x-select label="Select Brand" name="brand">
                            <option value="">
                                {{ __('Select Brand') }}
                            </option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="col-md-6 col-lg-4 mt-4">
                        <label class="form-label">{{ __('Select Color') }}</label>
                        <select name="color[]" data-placeholder="Select Color" class="form-control colorSelect" multiple
                            style="width: 100%">
                            <option value="">
                                {{ __('Select Color') }}
                            </option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}" data-color="{{ $color->color_code }}"
                                    {{ in_array($color->id, $product->colors?->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4">
                        <x-select label="Select Unit" name="unit" placeholder="Select Unit">
                            <option value="">
                                {{ __('Select Unit') }}
                            </option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ $unit->id == $product->unit_id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="col-md-6 col-lg-4 mt-4">
                        <label class="form-label">{{ __('Select Size') }}</label>
                        <select name="sizeIds[]" data-placeholder="Select Size" class="form-control sizeSelector" multiple
                            style="width: 100%">
                            <option value="">
                                {{ __('Select Size') }}
                            </option>
                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" data-size="{{ $size->name }}"
                                    {{ in_array($size->id, $product->sizes?->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $size->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-lg-4 mt-4">
                        <label class="form-label d-flex align-items-center gap-2 justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                {{ __('Product SKU') }}
                                <span class="info" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="{{__('Create a unique product code. This will be used generate barcode')}}">
                                    <i class="bi bi-info"></i>
                                </span>
                            </div>
                            <span class="text-primary cursor-pointer" onclick="generateCode()">
                                {{ __('Generate Code') }}
                            </span>
                        </label>
                        <input type="text" id="barcode" name="code" placeholder="Ex: 134543" class="form-control"
                            value="{{ $product->code }}"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                        @error('code')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror
                    </div>

                    <!--######## Size wise price table ##########-->
                    <div class="mt-4" id="sizeBox" @if ($product->sizes->isEmpty()) style="display: none" @endif>
                        <p class="fw-bold mb-1">
                            {{ __('Size wise price') }}
                        </p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        {{ __('Size') }}
                                    </th>
                                    <th>
                                        {{ __('Extra Price') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="selectedSizesTableBody">
                                @foreach ($product->sizes as $size)
                                    <tr id="selectedSizeRow_{{ $size->id }}">
                                        <td>
                                            <h4>{{ $size->name }}</h4>
                                            <input type="hidden" name="size[{{ $size->id }}][name]"
                                                value="{{ $size->name }}">
                                            <input type="hidden" name="size[{{ $size->id }}][id]"
                                                value="{{ $size->id }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                name="size[{{ $size->id }}][price]"
                                                value="{{ $size->pivot->price }}" style="max-width: 320px">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!--######## Price Information ##########-->
        <div class="card mt-4 mb-4">
            <div class="card-body">

                <div class="d-flex gap-2 border-bottom pb-2">
                    <i class="fa-solid fa-user"></i>
                    <h5>
                        {{ __('Price Information') }}
                    </h5>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-3 col-md-6">
                        <x-input type="text" name="price" label="Product Price" placeholder="Product Price"
                            required="true" onlyNumber="true" :value="$product->price" />
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3 mt-md-0">
                        <x-input type="text" name="discount_price" label="Discount Price"
                            placeholder="Discount Price" onlyNumber="true" :value="$product->discount_price" />
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
                        <x-input type="text" name="quantity" label="Current Stock Quantity"
                            placeholder="Current Stock Quantity" onlyNumber="true" required="true" :value="$product->quantity" />
                    </div>

                    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
                        <x-input type="text" onlyNumber="true" name="min_order_quantity"
                            label="Minimum Order Quantity" placeholder="Minimum Order Quantity" required="true"
                            :value="$product->min_order_quantity" />
                    </div>

                </div>
            </div>
        </div>

        <!--######## Thumbnail Information ##########-->
        <div class="row mb-3">
            <div class="col-md-5 col-xl-3">
                <div class="card card-body h-100">
                    <div class="mb-2">
                        <h5>
                            {{ __('Thumbnail') }}
                            <span class="text-primary">(Ratio 1:1 (500 x 500 px)</span> *
                        </h5>
                        @error('thumbnail')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <label for="thumbnail" class="additionThumbnail">
                        <img src="{{ $product->thumbnail ?? asset('defualt/upload.png') }}" id="preview"
                            alt="" width="100%">
                    </label>
                    <input id="thumbnail" accept="image/*" type="file" name="thumbnail" class="d-none"
                        onchange="previewFile(event, 'preview')">
                </div>
            </div>

            <div class="col-md-7 col-xl-9 mt-3 mt-md-0">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5>
                                {{ __('Additional Thumbnail') }}
                                <span class="text-primary">(Ratio 1:1 (500 x 500 px)</span> *
                            </h5>
                            @error('additionThumbnail')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-3" id="additionalElements">

                            <!-- previous additional thumbnail -->
                            @foreach ($product->medias as $media)
                                @php
                                    $source = asset('defualt/upload.png');
                                    if (Storage::exists($media->src)) {
                                        $source = Storage::url($media->src);
                                    }
                                @endphp

                                <div id="additionShow">
                                    <label for="previousThumbnailShow{{ $media->id }}" class="additionThumbnail">
                                        <img src="{{ $source }}" id="previewShow{{ $media->id }}"
                                            alt="thumbnail" width="100%" height="100%">
                                        <a href="{{ route('shop.product.remove.thumbnail', ['product' => $product->id, 'media' => $media->id]) }}"
                                            class="delete btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </label>
                                    <input type="hidden" name="previousThumbnail[{{ $loop->index }}][id]"
                                        value="{{ $media->id }}">
                                    <input id="previousThumbnailShow{{ $media->id }}" accept="image/*" type="file"
                                        name="previousThumbnail[{{ $loop->index }}][file]" class="d-none"
                                        onchange="previewFile(event, 'previewShow{{ $media->id }}')" />
                                </div>
                            @endforeach

                            <!-- New additional thumbnail -->
                            <div id="addition">
                                <label for="additionThumbnail1" class="additionThumbnail">
                                    <img src="{{ asset('defualt/upload.png') }}" id="preview2" alt=""
                                        width="100%" height="100%">
                                    <button onclick="removeThumbnail('addition')" id="removeThumbnail1" type="button"
                                        class="delete btn btn-sm btn-outline-danger" style="display: none"><i
                                            class="fa fa-trash"></i></button>
                                </label>
                                <input id="additionThumbnail1" accept="image/*" type="file"
                                    name="additionThumbnail[]" class="d-none"
                                    onchange="previewAdditionalFile(event, 'preview2', 'removeThumbnail1')">
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 justify-content-end align-items-center mb-3">
            <button type="reset" class="btn btn-lg btn-outline-secondary rounded py-2">
                {{ __('Reset') }}
            </button>
            <button type="submit" class="btn btn-lg btn-primary rounded py-2 px-5">
                {{ __('Update') }}
            </button>
        </div>

    </form>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.sizeSelector').select2();

            $('.sizeSelector').on('change', function() {

                // Get the selected options
                var selectedOptions = $(this).find(':selected');

                // Check if there are selected options
                if (selectedOptions.length > 0) {
                    $('#sizeBox').show();
                } else {
                    $('#sizeBox').hide();
                }

                selectedOptions.each(function() {
                    var sizeName = $(this).data('size');
                    var sizeId = $(this).val();

                    // Check if the row already exists
                    if (!$(`#selectedSizeRow_${sizeId}`).length) {
                        $('#selectedSizesTableBody').append(`
                    <tr id="selectedSizeRow_${sizeId}" style="display: table-row !important">
                        <td>
                            <h4>${sizeName}</h4>
                            <input type="hidden" name="size[${sizeId}][name]" value="${sizeName}">
                            <input type="hidden" name="size[${sizeId}][id]" value="${sizeId}">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="size[${sizeId}][price]" value="0" style="max-width: 320px">
                        </td>
                    </tr>
                `);
                    }
                });

                $(this).find(':not(:selected)').each(function() {
                    var sizeId = $(this).val();

                    // Remove the row from the table
                    $(`#selectedSizeRow_${sizeId}`).remove();
                });
            });

            // Get the category ID
            $('select[name="category"]').on('change', function() {
                var categoryId = $(this).val();

                if (categoryId) {
                    $.ajax({
                        url: '/api/sub-categories?category_id=' + categoryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var subCategorySelect = $('select[name="sub_category[]"]');
                            subCategorySelect.empty();

                            $.each(data.data.sub_categories, function(key, value) {
                                console.log(value, key);
                                subCategorySelect.append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                            subCategorySelect.trigger('change');
                        },
                        error: function() {
                            alert('Error retrieving subcategories. Please try again.');
                        }
                    });
                } else {
                    $('select[name="subCategory[]"]').empty();
                }
            });

        });
    </script>
    <!-- additional thumbnail script -->
    <script>
        var thumbnailCount = 1;

        const previewAdditionalFile = (event, id, removeId) => {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(id);
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);

            // increment count
            thumbnailCount++;

            document.getElementById(removeId).style.display = 'block';

            // Create a new box dynamically
            const newThumbnailId = `additionThumbnail${thumbnailCount + 1}`;
            const newPreviewId = `preview${thumbnailCount + 1}`;
            const mainId = 'addition' + thumbnailCount + 1;

            // Add the new box
            const newThumbnailBox = document.createElement('div');
            newThumbnailBox.id = mainId;

            newThumbnailBox.innerHTML = `
            <label for="${newThumbnailId}" class="additionThumbnail">
                <img src="{{ asset('defualt/upload.png') }}" id="${newPreviewId}" alt="" width="100%" height="100%">
                <button onclick="removeThumbnail('${mainId}')" type="button" id="removeThumbnail${thumbnailCount + 1}" class="delete btn btn-sm btn-outline-danger" style="display: none"><i class="fa fa-trash"></i></button>
                <input id="${newThumbnailId}" accept="image/*" type="file" name="additionThumbnail[]" class="d-none" onchange="previewAdditionalFile(event, '${newPreviewId}', 'removeThumbnail${thumbnailCount +1 }')">
            </label>
        `;

            document.getElementById('additionalElements').appendChild(newThumbnailBox);

            // get current file
            var inputElement = event.target;
            var newOnchangeFunction = `previewFile(event, '${id}')`;
            // Set the new onchange attribute
            inputElement.setAttribute("onchange", newOnchangeFunction);

        }

        const removeThumbnail = (thumbnailId) => {
            const thumbnailToRemove = document.getElementById(thumbnailId);
            if (thumbnailToRemove) {
                thumbnailToRemove.parentNode.removeChild(thumbnailToRemove);
            }
        }

        const generateCode = () => {
            const code = document.getElementById('barcode');
            code.value = Math.floor(Math.random() * 900000) + 100000;
        }
    </script>

    <!-- color select2 script -->
    <script>
        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(
                '<span class="d-flex align-items-center"> <span style="background-color:' + state.element.dataset
                .color +
                ';width:20px;height:20px;display:inline-block; border-radius:5px;margin-right:5px;"></span>' + state
                .text + '</span>'
            );
            return $state;
        };

        $(document).ready(function() {
            $('.colorSelect').select2({
                templateResult: formatState
            });
        });
    </script>

    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    [{
                        'font': []
                    }],
                    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['link', 'image', 'video', 'formula']
                ]
            }
        });

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('description').value = quill.root.innerHTML;
        });
    </script>
@endpush
