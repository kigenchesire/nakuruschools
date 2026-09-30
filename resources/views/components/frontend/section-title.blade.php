@props(['eyebrow' => null, 'title', 'center' => false, 'dark' => false, 'tag' => 'h2'])
<div {{ $attributes->merge(['class' => 'section-heading' . ($center ? ' text-center' : '')]) }}>
    @if ($eyebrow)
        <span class="eyebrow {{ $dark ? 'on-dark' : '' }}">{{ $eyebrow }}</span>
    @endif
    <{{ $tag }} class="section-title">{{ $title }}</{{ $tag }}>
    @if ($slot->isNotEmpty())
        <p>{{ $slot }}</p>
    @endif
</div>
