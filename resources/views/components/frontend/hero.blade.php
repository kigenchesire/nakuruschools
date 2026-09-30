@props(['sliders'])
@php $count = $sliders->count(); @endphp
<section class="hero" aria-label="Featured">
    <div id="heroCarousel" class="carousel slide carousel-fade" @if ($count > 1) data-bs-ride="carousel" data-bs-interval="7000" @endif>
        @if ($count > 1)
            <div class="carousel-indicators">
                @foreach ($sliders as $slide)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}" @if ($loop->first) aria-current="true" @endif
                            aria-label="Slide {{ $loop->iteration }}: {{ $slide->title }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner">
            @forelse ($sliders as $slide)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="hero-media {{ $slide->image_url ? '' : 'brand-pattern' }}">
                        @if ($slide->image_url)
                            {{-- The first slide is the page's main image, so it loads eagerly. --}}
                            <img src="{{ $slide->image_url }}" alt="" @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif decoding="async">
                        @endif
                    </div>
                    <div class="container hero-content">
                        <div class="hero-animate">
                            @if ($slide->subtitle)
                                <span class="hero-eyebrow">{{ $slide->subtitle }}</span>
                            @endif
                            @if ($loop->first)
                                <h1 class="hero-title">{{ $slide->title }}</h1>
                            @else
                                <h2 class="hero-title">{{ $slide->title }}</h2>
                            @endif
                            @if ($slide->description)
                                <p class="hero-text">{{ $slide->description }}</p>
                            @endif
                            <div class="d-flex flex-wrap gap-3">
                                @if ($slide->button_text && $slide->button_url)
                                    <a href="{{ $slide->button_url }}" class="btn btn-gold btn-lg btn-icon-end">
                                        {{ $slide->button_text }} <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                                    </a>
                                @endif
                                <a href="{{ route('about') }}" class="btn btn-outline-light-soft btn-lg">Discover Our School</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-item active">
                    <div class="hero-media brand-pattern"></div>
                    <div class="container hero-content">
                        <div class="hero-animate">
                            <span class="hero-eyebrow">{{ $site->schoolName() }}</span>
                            <h1 class="hero-title">{{ $site->setting('tagline') }}</h1>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('contact') }}" class="btn btn-gold btn-lg">{{ $site->setting('enquire_button_text') }}</a>
                                <a href="{{ route('about') }}" class="btn btn-outline-light-soft btn-lg">Discover Our School</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($count > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <i class="bi bi-chevron-left fs-5" aria-hidden="true"></i><span class="visually-hidden">Previous slide</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <i class="bi bi-chevron-right fs-5" aria-hidden="true"></i><span class="visually-hidden">Next slide</span>
            </button>
        @endif
    </div>
</section>
