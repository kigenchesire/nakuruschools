@props(['image', 'group' => 'gallery'])
<a href="{{ $image->image_url }}" class="gallery-item glightbox" data-gallery="{{ $group }}"
   data-description="{{ $image->caption }}" aria-label="View photo: {{ $image->alt_text }}">
    <img src="{{ $image->thumbnail_url }}" alt="{{ $image->alt_text }}" loading="lazy" decoding="async">
    <span class="gallery-overlay" aria-hidden="true">
        <span class="gallery-zoom"><i class="bi bi-arrows-fullscreen"></i></span>
        @if ($image->album)
            <small>{{ $image->album->name }}</small>
        @endif
        @if ($image->caption)
            <span>{{ $image->caption }}</span>
        @endif
    </span>
</a>
