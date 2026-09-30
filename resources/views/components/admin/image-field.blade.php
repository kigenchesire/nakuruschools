{{-- Image upload with live preview of the current / newly chosen image. --}}
@props([
    'name' => 'image',
    'label' => 'Image',
    'current' => null,
    'required' => false,
    'help' => 'JPG, PNG or WebP, up to 5 MB. Large photos are resized automatically.',
    'removable' => false,
    'accept' => 'image/jpeg,image/png,image/webp',
])
@php $id = 'field-' . $name; @endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    <div class="image-preview mb-2" id="{{ $id }}-preview">
        @if ($current)
            <img src="{{ $current }}" alt="Current {{ strtolower($label) }}">
        @else
            <div class="placeholder-text"><i class="bi bi-image" aria-hidden="true"></i>No image selected</div>
        @endif
    </div>
    <input type="file" name="{{ $name }}" id="{{ $id }}" accept="{{ $accept }}" data-preview="#{{ $id }}-preview"
           class="form-control @error($name) is-invalid @enderror" @if ($required && ! $current) required @endif aria-describedby="{{ $id }}-help">
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text" id="{{ $id }}-help">{{ $current ? 'Choose a new file to replace the current image. ' : '' }}{{ $help }}</div>
    @if ($removable && $current)
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="remove_{{ $name }}" value="1" id="{{ $id }}-remove">
            <label class="form-check-label small" for="{{ $id }}-remove">Remove current image</label>
        </div>
    @endif
</div>
