@php $tel = fn ($n) => preg_replace('/[^0-9+]/', '', (string) $n); @endphp
@if ($contact->physical_address)
    <div class="contact-card reveal">
        <span class="icon-badge" aria-hidden="true"><i class="bi bi-geo-alt-fill"></i></span>
        <div>
            <h3>Location</h3>
            <p>{{ $contact->physical_address }}</p>
            @if ($contact->postal_address)
                <p class="fw-normal text-body-secondary small mt-1">{{ $contact->postal_address }}</p>
            @endif
        </div>
    </div>
@endif
@if ($contact->phone || $contact->alt_phone)
    <div class="contact-card reveal">
        <span class="icon-badge" aria-hidden="true"><i class="bi bi-telephone-fill"></i></span>
        <div>
            <h3>Call Us</h3>
            @foreach (array_filter([$contact->phone, $contact->alt_phone]) as $phone)
                <a class="d-block" href="tel:{{ $tel($phone) }}">{{ $phone }}</a>
            @endforeach
        </div>
    </div>
@endif
@if ($contact->email || $contact->alt_email)
    <div class="contact-card reveal">
        <span class="icon-badge" aria-hidden="true"><i class="bi bi-envelope-fill"></i></span>
        <div>
            <h3>Email Us</h3>
            @foreach (array_filter([$contact->email, $contact->alt_email]) as $email)
                <a class="d-block" href="mailto:{{ $email }}">{{ $email }}</a>
            @endforeach
        </div>
    </div>
@endif
@if ($contact->office_hours)
    <div class="contact-card reveal">
        <span class="icon-badge" aria-hidden="true"><i class="bi bi-clock-fill"></i></span>
        <div>
            <h3>Office Hours</h3>
            <p>{{ $contact->office_hours }}</p>
        </div>
    </div>
@endif
