@extends('layouts.app')

@section('title', $category ? $category . ' — News' : 'News & Announcements')
@section('meta_description', 'Latest news, announcements, events and achievements from ' . $site->schoolName() . '.')

@section('content')
    <x-frontend.page-header title="News & Announcements" subtitle="Stories, updates and achievements from across our school community." />

    <section class="section">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-5">
                <nav class="filter-pills" aria-label="Filter news by category">
                    <a href="{{ route('news.index') }}" class="{{ ! $category ? 'active' : '' }}" @if (! $category) aria-current="page" @endif>All</a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('news.index', ['category' => $cat]) }}" class="{{ $category === $cat ? 'active' : '' }}" @if ($category === $cat) aria-current="page" @endif>{{ $cat }}</a>
                    @endforeach
                </nav>
                <form action="{{ route('news.index') }}" method="get" role="search" class="flex-shrink-0" style="min-width: min(100%, 300px)">
                    @if ($category)<input type="hidden" name="category" value="{{ $category }}">@endif
                    <label for="news-search" class="visually-hidden">Search news</label>
                    <div class="input-group">
                        <input type="search" id="news-search" name="q" value="{{ $search }}" class="form-control" placeholder="Search news…">
                        <button class="btn btn-primary" type="submit" aria-label="Search"><i class="bi bi-search" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>

            @if ($featured)
                <article class="news-featured mb-5 reveal">
                    <a href="{{ route('news.show', $featured) }}" class="news-media d-block" tabindex="-1" aria-hidden="true">
                        @if ($featured->image_url)
                            <img src="{{ $featured->image_url }}" alt="" decoding="async">
                        @else
                            <span class="news-placeholder brand-pattern position-absolute top-0 start-0"><i class="bi bi-newspaper"></i></span>
                        @endif
                    </a>
                    <div class="news-body">
                        <span class="eyebrow mb-2">Featured{{ $featured->category ? ' · ' . $featured->category : '' }}</span>
                        <h2 class="mb-3"><a href="{{ route('news.show', $featured) }}">{{ $featured->title }}</a></h2>
                        <p class="text-body-secondary">{{ \Illuminate\Support\Str::limit($featured->excerpt, 220) }}</p>
                        <div class="small text-body-secondary mb-4">
                            <i class="bi bi-calendar3 text-red me-1" aria-hidden="true"></i>{{ $featured->published_at->format('j F Y') }}
                            <span class="mx-2">·</span>{{ $featured->author_name }}
                        </div>
                        <div><a href="{{ route('news.show', $featured) }}" class="btn btn-primary btn-icon-end">Read Story <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a></div>
                    </div>
                </article>
            @endif

            @if ($news->isNotEmpty())
                <div class="row g-4">
                    @foreach ($news as $article)
                        <div class="col-md-6 col-lg-4">
                            <div class="h-100 reveal"><x-frontend.news-card :article="$article" heading-tag="h2" /></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $news->links() }}</div>
            @else
                <div class="empty-state">
                    <i class="bi bi-newspaper" aria-hidden="true"></i>
                    <h2 class="h5">No articles found</h2>
                    <p class="mb-3">{{ $search || $category ? 'Try a different search or category.' : 'News and announcements will appear here soon.' }}</p>
                    @if ($search || $category)
                        <a href="{{ route('news.index') }}" class="btn btn-outline-primary">View all news</a>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
