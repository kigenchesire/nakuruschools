@props(['article', 'headingTag' => 'h3'])
<article class="news-card">
    <a href="{{ route('news.show', $article) }}" class="news-media d-block" tabindex="-1" aria-hidden="true">
        @if ($article->image_url)
            <img src="{{ $article->image_url }}" alt="" loading="lazy" decoding="async" width="640" height="400">
        @else
            <span class="news-placeholder brand-pattern"><i class="bi bi-newspaper"></i></span>
        @endif
        @if ($article->category)
            <span class="news-category">{{ $article->category }}</span>
        @endif
    </a>
    <div class="news-body">
        <div class="news-meta">
            <span><i class="bi bi-calendar3" aria-hidden="true"></i><time datetime="{{ $article->published_at?->toDateString() }}">{{ $article->published_at?->format('j M Y') }}</time></span>
            <span><i class="bi bi-clock" aria-hidden="true"></i>{{ $article->reading_minutes }} min read</span>
        </div>
        <{{ $headingTag }}><a href="{{ route('news.show', $article) }}">{{ $article->title }}</a></{{ $headingTag }}>
        <p>{{ \Illuminate\Support\Str::limit($article->excerpt, 130) }}</p>
        <a href="{{ route('news.show', $article) }}" class="link-arrow mt-auto">Read More <span class="visually-hidden">about {{ $article->title }}</span><i class="bi bi-arrow-right" aria-hidden="true"></i></a>
    </div>
</article>
