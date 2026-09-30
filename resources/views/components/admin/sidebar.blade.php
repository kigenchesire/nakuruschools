@php
    $menu = [
        null => [
            ['admin.dashboard', 'Dashboard', 'bi-grid-1x2', 'admin.dashboard'],
        ],
        'Content' => [
            ['admin.sliders.index', 'Sliders', 'bi-collection-play', 'admin.sliders.*'],
            ['admin.about.index', 'About Us', 'bi-info-circle', 'admin.about.*'],
            ['admin.features.index', 'Why Choose Us', 'bi-stars', 'admin.features.*'],
            ['admin.news.index', 'News', 'bi-newspaper', 'admin.news.*'],
            ['admin.gallery.index', 'Gallery', 'bi-images', 'admin.gallery.*'],
            ['admin.faqs.index', 'FAQs', 'bi-question-circle', 'admin.faqs.*'],
            ['admin.resources.index', 'Resources', 'bi-folder2-open', 'admin.resources.*'],
        ],
        'Website' => [
            ['admin.contact.edit', 'Contact Information', 'bi-telephone', 'admin.contact.*'],
            ['admin.social.edit', 'Social Media', 'bi-share', 'admin.social.*'],
        ],
        'Messages' => [
            ['admin.enquiries.index', 'Contact Enquiries', 'bi-envelope', 'admin.enquiries.*'],
        ],
        'System' => [
            ['admin.users.index', 'Users', 'bi-people', 'admin.users.*'],
            ['admin.settings.edit', 'Settings', 'bi-gear', 'admin.settings.*'],
        ],
    ];
@endphp

<aside class="admin-sidebar " tabindex="-1" id="adminSidebar" aria-label="Admin navigation">
    <div class="d-flex align-items-center justify-content-between pe-2">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand text-decoration-none flex-grow-1">
            @if ($site->logoUrl())
                <img src="{{ $site->logoUrl() }}" alt="">
            @else
                <x-frontend.logo-mark style="height:36px" />
            @endif
            <span>{{ \Illuminate\Support\Str::limit($site->schoolName(), 20) }}<small>Admin Panel</small></span>
        </a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close menu"></button>
    </div>

    <nav class="sidebar-nav">
        @foreach ($menu as $heading => $items)
            @if ($heading)
                <div class="nav-heading">{{ $heading }}</div>
            @endif
            @foreach ($items as [$route, $label, $icon, $pattern])
                @php $active = request()->routeIs($pattern); @endphp
                <a href="{{ route($route) }}" class="nav-link {{ $active ? 'active' : '' }}" @if ($active) aria-current="page" @endif>
                    <i class="bi {{ $icon }}" aria-hidden="true"></i>
                    <span>{{ $label }}</span>
                    @if ($route === 'admin.enquiries.index' && $unreadEnquiries > 0)
                        <span class="badge rounded-pill text-bg-danger">{{ $unreadEnquiries }}<span class="visually-hidden"> unread</span></span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('home') }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i> View live website</a>
    </div>
</aside>
