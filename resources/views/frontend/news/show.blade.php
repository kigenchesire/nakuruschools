@extends('layouts.app')

@section('title', $news->title)
@section('meta_description', $news->meta_description ?: $news->excerpt)
@section('meta_keywords', implode(', ', array_filter([$news->category, $site->schoolName(), 'school news'])))
@section('og_type', 'article')
@if ($news->image_url)
    @section('og_image', $news->image_url)
@endif

@push('meta')
    <meta property="article:published_time" content="{{ $news->published_at->toAtomString() }}">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $news->title,
        'datePublished' => $news->published_at->toAtomString(),
        'dateModified' => $news->updated_at->toAtomString(),
        'image' => $news->image_url ? [$news->image_url] : [],
        'author' => ['@type' => 'Organization', 'name' => $site->schoolName()],
        'publisher' => ['@type' => 'Organization', 'name' => $site->schoolName()],
    ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
    <x-frontend.page-header :title="$news->title" :crumbs="['News' => route('news.index')]">
        <div class="d-flex flex-wrap gap-3 small mt-3" style="color: rgba(255,255,255,.8)">
            <span><i class="bi bi-calendar3 text-gold me-1" aria-hidden="true"></i><time datetime="{{ $news->published_at->toDateString() }}">{{ $news->published_at->format('j F Y') }}</time></span>
            <span><i class="bi bi-person text-gold me-1" aria-hidden="true"></i>{{ $news->author_name }}</span>
            <span><i class="bi bi-clock text-gold me-1" aria-hidden="true"></i>{{ $news->reading_minutes }} min read</span>
            @if ($news->category)
                <a href="{{ route('news.index', ['category' => $news->category]) }}" class="badge rounded-pill text-bg-warning align-self-center">{{ $news->category }}</a>
            @endif
        </div>
    </x-frontend.page-header>

    <article class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8">
                    @if ($news->image_url)
                        <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="article-hero-img mb-5" decoding="async">
                    @endif

                    <div class="prose">{!! $news->content !!}</div>

                    <hr class="my-5">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <a href="{{ route('news.index') }}" class="link-arrow"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to all news</a>
                        <div class="d-flex align-items-center gap-2 share-links">
                            <span class="small fw-semibold text-body-secondary me-1">Share:</span>
                            @php $shareUrl = urlencode(route('news.show', $news)); $shareTitle = urlencode($news->title); @endphp
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" aria-label="Share on X"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
                            <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="bi bi-whatsapp" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="section bg-soft">
            <div class="container">
                <x-frontend.section-title eyebrow="Keep Reading" title="More news" />
                <div class="row g-4">
                    @foreach ($related as $article)
                        <div class="col-md-6 col-lg-4"><x-frontend.news-card :article="$article" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
