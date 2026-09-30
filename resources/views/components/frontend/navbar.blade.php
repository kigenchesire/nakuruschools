@php
    $contact = $site->contact();
    $links = [
        ['route' => 'home', 'label' => 'Home', 'active' => 'home'],
        ['route' => 'about', 'label' => 'About Us', 'active' => 'about'],
        ['route' => 'news.index', 'label' => 'News', 'active' => 'news.*'],
        ['route' => 'gallery', 'label' => 'Gallery', 'active' => 'gallery'],
        ['route' => 'resources.index', 'label' => 'Resources', 'active' => 'resources.*'],
        ['route' => 'faqs', 'label' => 'FAQs', 'active' => 'faqs'],
        ['route' => 'contact', 'label' => 'Contact Us', 'active' => 'contact'],
    ];
@endphp

{{-- Top bar --}}
<div class="topbar d-none d-md-block">
    <div class="container d-flex align-items-center justify-content-between py-2 gap-3">
        <div class="d-flex flex-wrap align-items-center gap-4">
            @if ($contact->phone)
                <a class="topbar-item" href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}"><i class="bi bi-telephone-fill" aria-hidden="true"></i> {{ $contact->phone }}</a>
            @endif
            @if ($contact->email)
                <a class="topbar-item" href="mailto:{{ $contact->email }}"><i class="bi bi-envelope-fill" aria-hidden="true"></i> {{ $contact->email }}</a>
            @endif
            @if ($contact->office_hours)
                <span class="topbar-item d-none d-xl-inline-flex"><i class="bi bi-clock-fill" aria-hidden="true"></i> {{ $contact->office_hours }}</span>
            @endif
        </div>
        <x-frontend.social-icons variant="light" size="sm" />
    </div>
</div>

{{-- Main navigation --}}
<header class="site-header">
    <nav class="navbar navbar-expand-lg" aria-label="Main navigation">
        <div class="container">
            <x-frontend.brand class="navbar-brand me-lg-4 py-0" />

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto mb-3 mb-lg-0 align-items-lg-center">
                    @foreach ($links as $link)
                        @php $isActive = request()->routeIs($link['active']); @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ route($link['route']) }}" @if ($isActive) aria-current="page" @endif>{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('contact') }}#contact-form" class="btn btn-red ms-lg-3 d-flex d-lg-inline-flex justify-content-center align-items-center gap-2">
                    <i class="bi bi-chat-dots-fill" aria-hidden="true"></i> {{ $site->setting('enquire_button_text') }}
                </a>
                @if ($contact->phone)
                    <a class="d-lg-none d-flex align-items-center justify-content-center gap-2 mt-3 fw-semibold" href="tel:{{ preg_replace('/[^0-9+]/', '', $contact->phone) }}">
                        <i class="bi bi-telephone-fill text-red" aria-hidden="true"></i> {{ $contact->phone }}
                    </a>
                @endif
            </div>
        </div>
    </nav>
</header>
