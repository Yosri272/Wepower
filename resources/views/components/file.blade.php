<div>
    @if (isset($label))
        <label for="{{ $name }}" class="form-label">{{ __($label) }}</label>
    @endif
    <input type="file" name="{{ $name }}" id="{{ $name }}" class="form-control @error($name) is-invalid
    @enderror" @if($preview) onchange="previewFile(event,'{{ $preview }}')" @endif/>
    @error($name)
        <p class="text text-danger m-0">{{ $message }}</p>
    @enderror
</div>
