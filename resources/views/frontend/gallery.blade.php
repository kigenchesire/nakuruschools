@extends('layouts.app')

@section('title', $current ? $current->name . ' — Gallery' : 'Gallery')
@section('meta_description', $current?->description ?: 'Photos of school life, academics, sports, events and facilities at ' . $site->schoolName() . '.')

@section('content')
    <x-frontend.page-header title="Gallery" :subtitle="$current?->description ?: 'A glimpse into everyday life, learning and celebration at our school.'" />

    <section class="section">
        <div class="container">
            @if ($albums->isNotEmpty())
                <nav class="filter-pills mb-5 justify-content-lg-center" aria-label="Filter photos by album">
                    <a href="{{ route('gallery') }}" class="{{ ! $current ? 'active' : '' }}" @if (! $current) aria-current="page" @endif>All Photos</a>
                    @foreach ($albums as $album)
                        <a href="{{ route('gallery', ['album' => $album->slug]) }}" class="{{ $current?->is($album) ? 'active' : '' }}" @if ($current?->is($album)) aria-current="page" @endif>
                            {{ $album->name }} <span class="count">({{ $album->images_count }})</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            @if ($images->isNotEmpty())
                <div class="gallery-masonry">
                    @foreach ($images as $image)
                        <x-frontend.gallery-card :image="$image" />
                    @endforeach
                </div>
                <div class="mt-5">{{ $images->links() }}</div>
            @else
                <div class="empty-state">
                    <i class="bi bi-images" aria-hidden="true"></i>
                    <h2 class="h5">No photos yet</h2>
                    <p class="mb-0">Photos from our school will appear here soon.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
