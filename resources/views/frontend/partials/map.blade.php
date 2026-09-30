<div class="map-frame">
    @if ($contact->map_embed_url)
        <iframe src="{{ $contact->map_embed_url }}" title="Map showing the location of {{ $contact->school_name }}"
                loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    @else
        <div class="d-grid h-100 text-center p-5" style="place-items:center; min-height:360px;">
            <div>
                <i class="bi bi-map fs-1 text-navy opacity-50 d-block mb-2" aria-hidden="true"></i>
                <p class="text-body-secondary mb-0">Our map location will appear here soon.</p>
            </div>
        </div>
    @endif
</div>
@if ($contact->directions_url)
    <a href="{{ $contact->directions_url }}" target="_blank" rel="noopener" class="link-arrow mt-3">Get directions <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></a>
@endif
