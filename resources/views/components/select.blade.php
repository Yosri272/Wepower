<div>
    @if (isset($label) && $label)
        <label for="{{ $name }}" class="form-label">{{ __($label) }}</label>
    @endif
    <select @if ($placeholder) data-placeholder="{{ $placeholder }}" @endif name="{{ $name }}" id="{{ $name }}" class="form-control select2 @error($name) is-invalid @enderror" @if ($multiselect) multiple @endif style="width: 100%;">
        {{ $slot }}
    </select>
    @error($name)
        <p class="text text-danger m-0">{{ $message }}</p>
    @enderror
</div>
