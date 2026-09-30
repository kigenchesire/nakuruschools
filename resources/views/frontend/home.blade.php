@extends('layouts.app')

@php
    $intro = $about->get('introduction');
    $contact = $site->contact();
@endphp

@push('meta')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'School',
        'name' => $site->schoolName(),
        'url' => url('/'),
        'logo' => $site->logoUrl(),
        'telephone' => $contact->phone,
        'email' => $contact->email,
        'address' => $contact->physical_address,
        'sameAs' => $site->socialLinks()->pluck('url')->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')

    {{-- Hero slider --}}
    <x-frontend.hero :sliders="$sliders" />

    {{-- Welcome + key highlights --}}
    <section class="section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="{{ $highlights->isNotEmpty() ? 'col-lg-6' : 'col-lg-8 mx-auto text-center' }} reveal">
                    <span class="eyebrow">{{ $site->setting('home_welcome_eyebrow') }}</span>
                    <h2 class="section-title">{{ $site->setting('home_welcome_title') }}</h2>
                    <div class="welcome-text">
                        @foreach (preg_split('/\n\s*\n/', (string) $site->setting('home_welcome_text')) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-4 {{ $highlights->isNotEmpty() ? '' : 'justify-content-center' }}">
                        <a href="{{ route('about') }}" class="btn btn-primary btn-lg btn-icon-end">Learn More <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">Contact Us</a>
                    </div>
                </div>
                @if ($highlights->isNotEmpty())
                    <div class="col-lg-6">
                        <div class="stat-grid">
                            @foreach ($highlights->take(4) as $item)
                                <div class="stat-card reveal {{ $loop->iteration === 2 ? 'is-featured' : '' }}" style="transition-delay: {{ $loop->index * 80 }}ms">
                                    @if ($item->icon)
                                        <i class="bi {{ $item->icon }} stat-icon" aria-hidden="true"></i>
                                    @endif
                                    <div class="stat-value">{{ $item->value }}</div>
                                    <div class="stat-label">{{ $item->title }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Why choose us --}}
    @if ($whyChooseUs->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <x-frontend.section-title eyebrow="Why Choose Us" :title="'Why parents choose ' . $site->schoolName()" center class="reveal">
                    A nurturing environment where strong academics, sound values and rich experiences come together.
                </x-frontend.section-title>
                <div class="row g-4">
                    @foreach ($whyChooseUs as $feature)
                        <div class="col-sm-6 col-lg-3">
                            <div class="feature-card reveal" style="transition-delay: {{ ($loop->index % 4) * 70 }}ms">
                                <div class="icon-badge" aria-hidden="true"><i class="bi {{ $feature->icon ?: 'bi-star' }}"></i></div>
                                <h3>{{ $feature->title }}</h3>
                                @if ($feature->description)
                                    <p>{{ $feature->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- About the school (short) --}}
    @if ($intro)
        <section class="section">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 reveal">
                        <div class="about-media">
                            @if ($intro->image_url)
                                <img src="{{ $intro->image_url }}" alt="{{ $site->schoolName() }} learners and campus" loading="lazy" decoding="async">
                            @else
                                <div class="about-placeholder brand-pattern"><i class="bi bi-building fs-1"></i></div>
                            @endif
                            <div class="about-badge">
                                <span class="icon-badge" aria-hidden="true"><i class="bi bi-award-fill"></i></span>
                                <span><strong>{{ $site->setting('tagline') }}</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 reveal">
                        <span class="eyebrow">About Our School</span>
                        <h2 class="section-title">{{ $intro->title }}</h2>
                        @if ($intro->summary)
                            <p class="lead-lg">{{ $intro->summary }}</p>
                        @endif

                        <div class="row g-4 my-2">
                            @foreach (['mission' => 'bi-bullseye', 'vision' => 'bi-eye-fill'] as $key => $icon)
                                @if ($section = $about->get($key))
                                    <div class="col-md-6">
                                        <div class="mvv-card">
                                            <h3><i class="bi {{ $section->icon ?: $icon }}" aria-hidden="true"></i> {{ $section->title }}</h3>
                                            <p>{{ $section->summary }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if (($values = $about->get('core_values')) && $values->value_list->isNotEmpty())
                            <h3 class="h6 text-uppercase fw-bold text-body-secondary mt-2 mb-3" style="letter-spacing:.1em; font-family: inherit;">{{ $values->title }}</h3>
                            <ul class="value-chips list-unstyled mb-4">
                                @foreach ($values->value_list as $value)
                                    <li class="value-chip"><i class="bi bi-check-circle-fill" aria-hidden="true"></i>{{ $value }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <a href="{{ route('about') }}" class="btn btn-primary btn-lg btn-icon-end">Learn More About Us <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Call to action --}}
    <x-frontend.cta :secondary-text="'Learn More'" :secondary-url="route('about')" class="pt-0" />

    {{-- Latest news --}}
    @if ($news->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-5">
                    <x-frontend.section-title eyebrow="News & Updates" title="Latest from our school" class="mb-0 reveal">
                        Stay up to date with announcements, events and achievements across our school community.
                    </x-frontend.section-title>
                    <a href="{{ route('news.index') }}" class="btn btn-outline-primary flex-shrink-0 reveal">View All News <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a>
                </div>
                <div class="row g-4">
                    @foreach ($news as $article)
                        <div class="col-md-6 col-lg-4 {{ $loop->iteration > 3 ? 'd-none d-lg-block' : '' }}">
                            <div class="h-100 reveal" style="transition-delay: {{ ($loop->index % 3) * 80 }}ms">
                                <x-frontend.news-card :article="$article" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Gallery preview --}}
    @if ($gallery->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-5">
                    <x-frontend.section-title eyebrow="Gallery" title="Our school life" class="mb-0 reveal">
                        Moments from our classrooms, fields, stages and celebrations.
                    </x-frontend.section-title>
                    <a href="{{ route('gallery') }}" class="btn btn-outline-primary flex-shrink-0 reveal">View Full Gallery <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a>
                </div>
                <div class="gallery-grid reveal">
                    @foreach ($gallery as $image)
                        <x-frontend.gallery-card :image="$image" group="home-gallery" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQs + resources --}}
    @if ($faqs->isNotEmpty() || $resources->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <div class="row g-5">
                    @if ($faqs->isNotEmpty())
                        <div class="{{ $resources->isNotEmpty() ? 'col-lg-7' : 'col-lg-10 mx-auto' }} reveal">
                            <span class="eyebrow">FAQs</span>
                            <h2 class="section-title">Frequently asked questions</h2>
                            <p class="text-body-secondary mb-4">Quick answers to the questions parents ask us most.</p>
                            <x-frontend.faq-accordion :faqs="$faqs" id="homeFaq" />
                            <a href="{{ route('faqs') }}" class="link-arrow mt-2">See all FAQs <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    @endif
                    @if ($resources->isNotEmpty())
                        <div class="{{ $faqs->isNotEmpty() ? 'col-lg-5' : 'col-lg-10 mx-auto' }} reveal">
                            <span class="eyebrow">Resources</span>
                            <h2 class="section-title">Downloads for parents</h2>
                            <p class="text-body-secondary mb-4">Admission forms, fee structures, calendars and more.</p>
                            <div class="d-grid gap-3">
                                @foreach ($resources as $resource)
                                    <x-frontend.resource-card :resource="$resource" />
                                @endforeach
                            </div>
                            <a href="{{ route('resources.index') }}" class="link-arrow mt-4">Browse all resources <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Contact & location --}}
    <section class="section">
        <div class="container">
            <x-frontend.section-title eyebrow="Visit Us" title="Get in touch" center class="reveal">
                We would love to hear from you. Visit, call or send us a message and our team will respond promptly.
            </x-frontend.section-title>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="d-grid gap-3 h-100">
                        @include('frontend.partials.contact-cards', ['contact' => $contact])
                    </div>
                </div>
                <div class="col-lg-7 reveal">
                    @include('frontend.partials.map', ['contact' => $contact])
                </div>
            </div>
        </div>
    </section>

@endsection
