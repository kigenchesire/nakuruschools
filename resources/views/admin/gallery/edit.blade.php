@extends('layouts.admin')

@section('title', 'Edit Image')

@section('content')
    <x-admin.breadcrumbs title="Edit Image" :crumbs="['Gallery' => route('admin.gallery.index')]" />

    <form action="{{ route('admin.gallery.update', $image) }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <x-admin.image-field name="image" label="Image" :current="$image->image_url" />
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="caption" label="Caption" :value="$image->caption" maxlength="200" help="Also used as the image’s alternative text for screen readers." />
                        <div class="mb-3">
                            <label for="gallery_album_id" class="form-label">Album</label>
                            <select name="gallery_album_id" id="gallery_album_id" class="form-select">
                                <option value="">— Uncategorised —</option>
                                @foreach ($albums as $album)
                                    <option value="{{ $album->id }}" @selected((int) old('gallery_album_id', $image->gallery_album_id) === $album->id)>{{ $album->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-admin.input name="sort_order" type="number" label="Sort order" :value="$image->sort_order" min="0" />
                        <x-admin.switch name="is_featured" label="Feature on homepage" :checked="$image->is_featured" />
                        <x-admin.switch name="is_active" label="Show on website" :checked="$image->is_active" />
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-actions mt-4 card">
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> Save Changes</button>
        </div>
    </form>
@endsection
