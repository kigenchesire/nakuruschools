@extends('layouts.admin')

@section('title', 'News')

@php
    $sortLink = function (string $column, string $label) use ($sort, $direction) {
        $next = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';
        $icon = $sort === $column ? ($direction === 'asc' ? 'bi-sort-up' : 'bi-sort-down') : 'bi-arrow-down-up opacity-25';
        $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $next, 'page' => null]);
        return '<a href="' . e($url) . '">' . e($label) . ' <i class="bi ' . $icon . '" aria-hidden="true"></i></a>';
    };
@endphp

@section('content')
    <x-admin.breadcrumbs title="News & Announcements" description="Create, publish and feature school news articles.">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> New Article</a>
    </x-admin.breadcrumbs>

    <div class="card">
        <div class="card-header">
            <form method="get" class="row g-2 align-items-center" role="search">
                <div class="col-md-6 col-lg-5">
                    <label for="q" class="visually-hidden">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                        <input type="search" name="q" id="q" value="{{ $search }}" class="form-control" placeholder="Search title or category…">
                    </div>
                </div>
                <div class="col-8 col-md-3">
                    <label for="status" class="visually-hidden">Status</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All statuses</option>
                        <option value="published" @selected($status === 'published')>Published</option>
                        <option value="draft" @selected($status === 'draft')>Drafts</option>
                        <option value="featured" @selected($status === 'featured')>Featured</option>
                    </select>
                </div>
                <div class="col-4 col-md-auto d-flex gap-2">
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                    @if ($search || $status)
                        <a href="{{ route('admin.news.index') }}" class="btn btn-link px-1">Reset</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">{!! $sortLink('title', 'Title') !!}</th>
                        <th scope="col" class="d-none d-lg-table-cell">Author</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="d-none d-md-table-cell">Featured</th>
                        <th scope="col" class="d-none d-md-table-cell">{!! $sortLink('published_at', 'Published') !!}</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($news as $article)
                        <tr>
                            <td>
                                @if ($article->image_url)
                                    <img src="{{ $article->image_url }}" alt="" class="table-thumb" loading="lazy">
                                @else
                                    <span class="table-thumb-placeholder"><i class="bi bi-image" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.news.edit', $article) }}" class="cell-title d-block">{{ \Illuminate\Support\Str::limit($article->title, 70) }}</a>
                                <span class="cell-sub">{{ $article->category ?: 'Uncategorised' }}</span>
                            </td>
                            <td class="d-none d-lg-table-cell small">{{ $article->author_name }}</td>
                            <td><x-admin.status-badge :active="$article->isPublished()" on="Published" off="Draft" /></td>
                            <td class="d-none d-md-table-cell">
                                <form action="{{ route('admin.news.feature', $article) }}" method="post">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-link p-0 {{ $article->is_featured ? 'text-warning' : 'text-body-secondary' }}"
                                            aria-label="{{ $article->is_featured ? 'Remove from featured' : 'Mark as featured' }}" data-bs-toggle="tooltip"
                                            title="{{ $article->is_featured ? 'Featured — click to remove' : 'Mark as featured' }}">
                                        <i class="bi {{ $article->is_featured ? 'bi-star-fill' : 'bi-star' }} fs-5" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="d-none d-md-table-cell small text-nowrap">
                                {{ $article->published_at?->format('j M Y') ?? '—' }}
                                @if ($article->isPublished() && $article->published_at?->isFuture())
                                    <div class="cell-sub text-info"><i class="bi bi-clock" aria-hidden="true"></i> Scheduled</div>
                                @endif
                            </td>
                            <td>
                                <x-admin.row-actions :name="$article->title" :edit="route('admin.news.edit', $article)" :destroy="route('admin.news.destroy', $article)">
                                    <a href="{{ route('admin.news.show', $article) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Preview" aria-label="Preview {{ $article->title }}"><i class="bi bi-eye" aria-hidden="true"></i></a>
                                    <form action="{{ route('admin.news.publish', $article) }}" method="post">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="{{ $article->isPublished() ? 'Unpublish' : 'Publish' }}" aria-label="{{ $article->isPublished() ? 'Unpublish' : 'Publish' }} {{ $article->title }}">
                                            <i class="bi {{ $article->isPublished() ? 'bi-cloud-slash text-warning' : 'bi-cloud-arrow-up text-success' }}" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </x-admin.row-actions>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-row"><i class="bi bi-newspaper" aria-hidden="true"></i>{{ $search || $status ? 'No articles match your filters.' : 'No articles yet.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($news->hasPages())
            <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <small class="text-body-secondary">Showing {{ $news->firstItem() }}–{{ $news->lastItem() }} of {{ $news->total() }}</small>
                {{ $news->links() }}
            </div>
        @endif
    </div>
@endsection
