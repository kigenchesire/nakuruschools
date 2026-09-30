@extends('layouts.admin')

@section('title', 'Upload Images')

@section('content')
    <x-admin.breadcrumbs title="Upload Images" :crumbs="['Gallery' => route('admin.gallery.index')]"
        description="Upload up to {{ \App\Http\Requests\Admin\GalleryImageRequest::MAX_FILES }} photos at once. They are resized automatically for fast loading." />

    <form action="{{ route('admin.gallery.store') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <label for="images" class="form-label required">Images</label>
                        <div class="image-preview mb-2" id="images-preview" style="min-height: 220px">
                            <div class="placeholder-text"><i class="bi bi-cloud-upload" aria-hidden="true"></i>Selected photos will be previewed here</div>
                        </div>
                        <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/webp" data-preview="#images-preview"
                               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" required aria-describedby="images-help">
                        @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @foreach ($errors->get('images.*') as $messages)
                            <div class="invalid-feedback d-block">{{ $messages[0] }}</div>
                        @endforeach
                        <div class="form-text" id="images-help">JPG, PNG or WebP. Max {{ \App\Support\UploadRules::IMAGE_MAX_KB / 1024 }} MB per image. Hold Ctrl (or Cmd) to select several files.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="gallery_album_id" class="form-label">Album</label>
                            <select name="gallery_album_id" id="gallery_album_id" class="form-select @error('gallery_album_id') is-invalid @enderror">
                                <option value="">— Uncategorised —</option>
                                @foreach ($albums as $album)
                                    <option value="{{ $album->id }}" @selected((int) old('gallery_album_id', $selectedAlbum) === $album->id)>{{ $album->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text"><a href="{{ route('admin.gallery.albums.create') }}">Create a new album</a></div>
                        </div>
                        <x-admin.input name="caption" label="Caption" maxlength="200" help="Applied to every image in this upload; you can edit captions individually later." />
                        <x-admin.switch name="is_featured" label="Feature on homepage" :checked="false" />
                        <x-admin.switch name="is_active" label="Show on website" :checked="true" />
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-actions mt-4 card">
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload me-1" aria-hidden="true"></i> Upload</button>
        </div>
    </form>
@endsection
