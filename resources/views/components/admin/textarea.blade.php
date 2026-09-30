{{-- Textarea; pass `editor` to turn it into a CKEditor rich-text field. --}}
@props(['name', 'label', 'value' => null, 'required' => false, 'help' => null, 'rows' => 4, 'editor' => false])
@php $id = 'field-' . $name; @endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" @if ($editor) data-editor @endif
              {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
              @if ($required && ! $editor) required @endif @if ($help) aria-describedby="{{ $id }}-help" @endif>{{ old($name, $value) }}</textarea>
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if ($help)
        <div class="form-text" id="{{ $id }}-help">{{ $help }}</div>
    @endif
</div>
