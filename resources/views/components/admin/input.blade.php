{{-- Text-like input with label, validation state and optional help text. --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'help' => null, 'prepend' => null])
@php
    $id = 'field-' . str_replace(['[', ']', '.'], '-', $name);
    $key = str_replace(['[', ']'], ['.', ''], $name);
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    @if ($prepend)
        <div class="input-group has-validation">
            <span class="input-group-text"><i class="bi {{ $prepend }}" aria-hidden="true"></i></span>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $type === 'password' ? '' : old($key, $value) }}"
           {{ $attributes->merge(['class' => 'form-control' . ($errors->has($key) ? ' is-invalid' : '')]) }}
           @if ($required) required @endif @if ($help) aria-describedby="{{ $id }}-help" @endif>
    @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($prepend)
        </div>
    @endif
    @if ($help)
        <div class="form-text" id="{{ $id }}-help">{{ $help }}</div>
    @endif
</div>
