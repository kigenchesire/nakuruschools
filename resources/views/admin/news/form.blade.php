@extends('layouts.admin')

@php $editing = $news->exists; @endphp
@section('title', $editing ? 'Edit Article' : 'New Article')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit Article' : 'New Article'" :crumbs="['News' => route('admin.news.index')]">
        @if ($editing && $news->isPublished())
            <a href="{{ route('news.show', $news) }}" target="_blank" rel="noopener" class="btn btn-outline-secondary"><i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i> View on site</a>
        @endif
    </x-admin.breadcrumbs>

    <form action="{{ $editing ? route('admin.news.update', $news) : route('admin.news.store') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$news->title" required maxlength="200" class="form-control-lg" />
                        <x-admin.input name="slug" label="URL slug" :value="$news->slug" maxlength="200" prepend="bi-link-45deg"
                            :help="$editing ? 'Changing the slug changes the article’s web address; old links will stop working.' : 'Leave blank to generate from the title.'" />
                        <x-admin.textarea name="content" label="Article content" :value="$news->content" editor rows="16" required />
                        <x-admin.textarea name="excerpt" label="Excerpt" :value="$news->excerpt" rows="3" maxlength="400" help="Short summary shown on news cards. Leave blank to use the start of the article." />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="form-section-title">Publishing</h2>
                        <div class="mb-3">
                            <label for="status" class="form-label required">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="draft" @selected(old('status', $news->status) === 'draft')>Draft — not visible</option>
                                <option value="published" @selected(old('status', $news->status) === 'published')>Published</option>
                            </select>
                        </div>
                        <x-admin.input name="published_at" type="datetime-local" label="Publish date" :value="$news->published_at?->format('Y-m-d\TH:i')" help="A future date schedules the article." />
                        <x-admin.switch name="is_featured" label="Featured article" :checked="$news->is_featured" help="Highlighted at the top of the News page." />
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <x-admin.image-field name="image" label="Featured image" :current="$news->image_url" removable help="Landscape, at least 1200×750. JPG, PNG or WebP up to 5 MB." />
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h2 class="form-section-title">Organise & SEO</h2>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input list="news-categories" name="category" id="category" value="{{ old('category', $news->category) }}" maxlength="60" class="form-control @error('category') is-invalid @enderror" placeholder="Choose or type…">
                            <datalist id="news-categories">
                                @foreach ($categories as $cat)<option value="{{ $cat }}">@endforeach
                            </datalist>
                        </div>
                        <x-admin.textarea name="meta_description" label="Meta description" :value="$news->meta_description" rows="3" maxlength="255" help="Shown in Google results and social shares. Defaults to the excerpt." />
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky-actions mt-4 card">
            <a href="{{ route('admin.news.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Create Article' }}</button>
        </div>
    </form>
@endsection
