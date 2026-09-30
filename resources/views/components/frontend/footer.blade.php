@php $contact = $site->contact(); @endphp
<footer class="site-footer pt-5">
    <div class="container pt-4 pb-4">
        <div class="row g-5">
            <div class="col-lg-4">
                <x-frontend.brand class="mb-4" />
                <p class="mb-4">{{ $site->setting('footer_about') }}</p>
                <x-frontend.social-icons variant="light" />
            </div>

            <div class="col-6 col-lg-2">
                <h2>Quick Links</h2>
                <ul class="list-unstyled footer-links mb-0">
                    <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> Home</a></li>
                    <li><a href="{{ route('about') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> About Us</a></li>
                    <li><a href="{{ route('news.index') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> News</a></li>
                    <li><a href="{{ route('gallery') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> Gallery</a></li>
                    <li><a href="{{ route('resources.index') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> Resources</a></li>
                    <li><a href="{{ route('contact') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> Contact</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h2>Parents</h2>
                <ul class="list-unstyled footer-links mb-0">
                    <li><a href="{{ route('faqs') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> FAQs</a></li>
                    <li><a href="{{ route('resources.index') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> Downloads</a></li>
                    <li><a href="{{ route('contact') }}#contact-form"><i class="bi bi-chevron-right" aria-hidden="true"></i> {{ $site->setting('enquire_button_text') }}</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h2>Contact Us</h2>
                <ul class="list-unstyled footer-contact mb-0">
                    @if ($contact->physical_address)
                        <li><i class="bi bi-geo-alt-fill" aria-hidden="true"></i><span>{{ $contact->physical_address }}</span></li>
                    @endif
                    @if ($contact->postal_address)
                        <li><i class="bi bi-mailbox2" aria-hidden="true"></i><span>{{ $contact->postal_address }}</span></li>
                    @endif
                    @if ($contact->phone)
                        <li><i class="bi bi-telephone-fill" aria-hidden="true"></i><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}">{{ $contact->phone }}</a></li>
                    @endif
                    @if ($contact->email)
                        <li><i class="bi bi-envelope-fill" aria-hidden="true"></i><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></li>
                    @endif
                    @if ($contact->office_hours)
                        <li><i class="bi bi-clock-fill" aria-hidden="true"></i><span>{{ $contact->office_hours }}</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container py-4 d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>{{ $site->setting('footer_text') ?: '© ' . date('Y') . ' ' . $site->schoolName() . '. All Rights Reserved.' }}</span>
            @if ($site->setting('powered_by'))
                <span>Powered by {{ $site->setting('powered_by') }}</span>
            @endif
        </div>
    </div>
</footer>
