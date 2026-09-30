@extends('layouts.app')

@section('title', 'About Us')
@section('meta_description', $intro?->summary ?: $site->setting('meta_description'))
@if ($intro?->image_url)
    @section('og_image', $intro->image_url)
@endif

@section('content')
    <x-frontend.page-header title="About Us" :subtitle="$intro?->subtitle ?: 'Get to know our story, our purpose and the values that guide everything we do.'" />

    {{-- Introduction --}}
    @if ($intro)
        <section class="section">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 order-lg-2 reveal">
                        <div class="about-media">
                            @if ($intro->image_url)
                                <img src="{{ $intro->image_url }}" alt="{{ $site->schoolName() }} campus" decoding="async">
                            @else
                                <div class="about-placeholder brand-pattern"><i class="bi bi-building fs-1"></i></div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1 reveal">
                        <span class="eyebrow">Who We Are</span>
                        <h2 class="section-title">{{ $intro->title }}</h2>
                        @if ($intro->content)
                            <div class="prose">{!! $intro->content !!}</div>
                        @elseif ($intro->summary)
                            <p class="lead-lg">{{ $intro->summary }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Mission / Vision / Values --}}
    @if ($mission || $vision || $values)
        <section class="section section-dark brand-pattern">
            <div class="container">
                <x-frontend.section-title eyebrow="Our Purpose" title="Mission, vision & core values" center dark class="reveal" />
                <div class="row g-4">
                    @foreach ([[$mission, 'bi-bullseye'], [$vision, 'bi-eye-fill']] as [$section, $icon])
                        @if ($section)
                            <div class="col-md-6 {{ $values ? 'col-lg-4' : '' }}">
                                <div class="feature-card reveal">
                                    <div class="icon-badge" aria-hidden="true"><i class="bi {{ $section->icon ?: $icon }}"></i></div>
                                    <h3>{{ $section->title }}</h3>
                                    <p>{{ $section->summary }}</p>
                                    @if ($section->content)
                                        <div class="prose small mt-3">{!! $section->content !!}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if ($values)
                        <div class="col-lg-4">
                            <div class="feature-card reveal">
                                <div class="icon-badge" aria-hidden="true"><i class="bi {{ $values->icon ?: 'bi-gem' }}"></i></div>
                                <h3>{{ $values->title }}</h3>
                                <ul class="value-chips list-unstyled mb-0 mt-3">
                                    @foreach ($values->value_list as $value)
                                        <li class="value-chip"><i class="bi bi-check-circle-fill" aria-hidden="true"></i>{{ $value }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Highlights --}}
    @if ($highlights->isNotEmpty())
        <section class="section-sm bg-soft">
            <div class="container">
                <div class="row g-3">
                    @foreach ($highlights as $item)
                        <div class="col-6 col-lg-3">
                            <div class="stat-card h-100 text-center reveal">
                                @if ($item->icon)<i class="bi {{ $item->icon }} stat-icon" aria-hidden="true"></i>@endif
                                <div class="stat-value">{{ $item->value }}</div>
                                <div class="stat-label">{{ $item->title }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- History --}}
    @if ($history)
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9 reveal">
                        <span class="eyebrow">Our Story</span>
                        <h2 class="section-title">{{ $history->title }}</h2>
                        @if ($history->image_url)
                            <img src="{{ $history->image_url }}" alt="{{ $history->title }}" class="article-hero-img my-4" loading="lazy">
                        @endif
                        <div class="prose">{!! $history->content ?: e($history->summary) !!}</div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Other information sections --}}
    @foreach ($others as $section)
        <section class="section {{ $loop->odd ? 'bg-soft' : '' }}">
            <div class="container">
                <div class="row g-5 align-items-center">
                    @if ($section->image_url)
                        <div class="col-lg-5 {{ $loop->even ? 'order-lg-2' : '' }} reveal">
                            <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="article-hero-img" loading="lazy">
                        </div>
                    @endif
                    <div class="{{ $section->image_url ? 'col-lg-7' : 'col-lg-9 mx-auto' }} reveal">
                        @if ($section->subtitle)<span class="eyebrow">{{ $section->subtitle }}</span>@endif
                        <h2 class="section-title">{{ $section->title }}</h2>
                        <div class="prose">{!! $section->content ?: e($section->summary) !!}</div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    <x-frontend.cta :secondary-text="'Read FAQs'" :secondary-url="route('faqs')" />
@endsection
