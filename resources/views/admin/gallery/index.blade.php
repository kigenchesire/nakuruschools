@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
    <x-admin.breadcrumbs title="Gallery" description="Photos shown on the Gallery page and homepage preview.">
        <a href="{{ route('admin.gallery.albums.index') }}" class="btn btn-outline-primary"><i class="bi bi-folder me-1" aria-hidden="true"></i> Manage Albums</a>
        <a href="{{ route('admin.gallery.create', array_filter(['album' => is_numeric($albumFilter) ? $albumFilter : null])) }}" class="btn btn-primary"><i class="bi bi-cloud-upload me-1" aria-hidden="true"></i> Upload Images</a>
    </x-admin.breadcrumbs>

    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="get" class="row g-2 align-items-center">
                <div class="col-sm-5 col-lg-4">
                    <label for="album" class="visually-hidden">Album</label>
                    <select name="album" id="album" class="form-select" onchange="this.form.submit()">
                        <option value="">All albums</option>
                        @foreach ($albums as $album)
                            <option value="{{ $album->id }}" @selected((string) $albumFilter === (string) $album->id)>{{ $album->name }}</option>
                        @endforeach
                        <option value="none" @selected($albumFilter === 'none')>Uncategorised</option>
                    </select>
                </div>
                <div class="col-sm-4 col-lg-3">
                    <label for="status" class="visually-hidden">Status</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Any status</option>
                        <option value="featured" @selected(request('status') === 'featured')>Featured only</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Hidden only</option>
                    </select>
                </div>
                <div class="col-sm-3 col-lg-auto">
                    <noscript><button class="btn btn-outline-primary">Filter</button></noscript>
                    @if (request()->hasAny(['album', 'status']))
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-link">Reset</a>
                    @endif
                </div>
                <div class="col-lg text-lg-end small text-body-secondary">{{ $images->total() }} {{ \Illuminate\Support\Str::plural('image', $images->total()) }}</div>
            </form>
        </div>
    </div>

    @if ($images->isNotEmpty())
        <div class="admin-gallery">
            @foreach ($images as $image)
                <div class="admin-gallery-item {{ $image->is_active ? '' : 'is-inactive' }}">
                    <div class="thumb">
                        <img src="{{ $image->thumbnail_url }}" alt="{{ $image->alt_text }}" loading="lazy">
                        <div class="badges">
                            @if ($image->is_featured)<span class="badge text-bg-warning"><i class="bi bi-star-fill me-1" aria-hidden="true"></i>Featured</span>@endif
                            @unless ($image->is_active)<span class="badge text-bg-secondary"><i class="bi bi-eye-slash me-1" aria-hidden="true"></i>Hidden</span>@endunless
                        </div>
                    </div>
                    <div class="meta">
                        <div class="fw-semibold text-truncate">{{ $image->caption ?: 'No caption' }}</div>
                        <div class="text-body-secondary text-truncate">{{ $image->album?->name ?? 'Uncategorised' }}</div>
                    </div>
                    <div class="actions">
                        <x-admin.row-actions :name="$image->caption ?: 'this image'"
                            :edit="route('admin.gallery.edit', $image)"
                            :toggle="route('admin.gallery.toggle', $image)" :active="$image->is_active"
                            :destroy="route('admin.gallery.destroy', $image)" />
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">{{ $images->links() }}</div>
    @else
        <div class="card"><div class="card-body empty-row"><i class="bi bi-images" aria-hidden="true"></i>No images found. <a href="{{ route('admin.gallery.create') }}">Upload some photos</a>.</div></div>
    @endif
@endsection
