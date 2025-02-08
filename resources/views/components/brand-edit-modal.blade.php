<div>
    <!--=== Edit Brand Modal ===-->
    <form action="{{ route('shop.brand.update', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal fade" id="editBrand{{ $brand->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Edit Brand')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align: left">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{__('Name')}} *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="{{__('Name')}}" value="{{ $brand->name }}" required />
                            @error('name')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">
                            {{__('Update')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
