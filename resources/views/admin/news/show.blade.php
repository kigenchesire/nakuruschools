@extends('layouts.admin')

@section('title', 'Preview: ' . $news->title)

@push('styles')
    <style>
        .prose { font-size: 1.05rem; line-height: 1.8; }
        .prose img { max-width: 100%; border-radius: .75rem; }
        .prose blockquote { border-left: 4px solid var(--school-yellow); padding: .75rem 1.25rem; background: #f8fafc; }
        .prose table { width: 100%; } .prose td, .prose th { border: 1px solid #e3e8ef; padding: .5rem; }
    </style>
@endpush

@section('content')
    <x-admin.breadcrumbs title="Article Preview" :crumbs="['News' => route('admin.news.index')]">
        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Edit</a>
        <form action="{{ route('admin.news.publish', $news) }}" method="post">
            @csrf @method('PATCH')
            <button class="btn {{ $news->isPublished() ? 'btn-warning' : 'btn-success' }}">
                <i class="bi {{ $news->isPublished() ? 'bi-cloud-slash' : 'bi-cloud-arrow-up' }} me-1" aria-hidden="true"></i>{{ $news->isPublished() ? 'Unpublish' : 'Publish' }}
            </button>
        </form>
    </x-admin.breadcrumbs>

    <div class="card">
        <div class="card-body p-lg-5" style="max-width: 900px">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <x-admin.status-badge :active="$news->isPublished()" on="Published" off="Draft" />
                @if ($news->is_featured)<span class="status-badge bg-warning-subtle text-warning-emphasis"><i class="bi bi-star-fill" aria-hidden="true"></i>Featured</span>@endif
                @if ($news->category)<span class="status-badge bg-primary-subtle text-primary-emphasis"><i class="bi bi-tag" aria-hidden="true"></i>{{ $news->category }}</span>@endif
            </div>
            <h2 class="h1 mb-2">{{ $news->title }}</h2>
            <p class="text-body-secondary small mb-4">
                {{ $news->published_at?->format('j F Y, g:i a') ?? 'Not scheduled' }} · {{ $news->author_name }} · {{ number_format($news->views) }} views
            </p>
            @if ($news->image_url)
                <img src="{{ $news->image_url }}" alt="" class="img-fluid rounded-4 mb-4">
            @endif
            @if ($news->excerpt)
                <p class="lead">{{ $news->excerpt }}</p>
            @endif
            <div class="prose">{!! $news->content !!}</div>
        </div>
    </div>
@endsection
