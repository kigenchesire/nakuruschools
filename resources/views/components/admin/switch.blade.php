@props(['name', 'label', 'checked' => false, 'help' => null])
@php $id = 'switch-' . $name; @endphp
<div class="form-check form-switch mb-3">
    <input type="hidden" name="{{ $name }}" value="0">
    <input class="form-check-input" type="checkbox" role="switch" name="{{ $name }}" value="1" id="{{ $id }}" @checked(old($name, $checked))>
    <label class="form-check-label fw-semibold" for="{{ $id }}">{{ $label }}</label>
    @if ($help)
        <div class="form-text mt-0">{{ $help }}</div>
    @endif
</div>
