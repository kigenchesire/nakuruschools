@extends('layouts.admin')

@php $editing = $resource->exists; @endphp
@section('title', $editing ? 'Edit Resource' : 'Upload Resource')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit Resource' : 'Upload Resource'" :crumbs="['Resources' => route('admin.resources.index')]" />

    <form action="{{ $editing ? route('admin.resources.update', $resource) : route('admin.resources.store') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$resource->title" required maxlength="200" placeholder="e.g. 2026 Fee Structure" />
                        <x-admin.textarea name="description" label="Description" :value="$resource->description" rows="3" maxlength="1000" />
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input list="resource-categories" name="category" id="category" value="{{ old('category', $resource->category) }}" maxlength="60" class="form-control @error('category') is-invalid @enderror" placeholder="Choose or type…">
                            <datalist id="resource-categories">
                                @foreach ($categories as $cat)<option value="{{ $cat }}">@endforeach
                            </datalist>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <label for="file" class="form-label {{ $editing ? '' : 'required' }}">{{ $editing ? 'Replace file' : 'File' }}</label>
                        @if ($editing)
                            <div class="d-flex align-items-center gap-2 p-2 mb-2 rounded border bg-light small">
                                <i class="bi {{ $resource->icon }} text-danger fs-4" aria-hidden="true"></i>
                                <div class="min-w-0">
                                    <div class="text-truncate fw-semibold">{{ $resource->original_name ?: $resource->downloadName() }}</div>
                                    <div class="text-body-secondary">{{ strtoupper($resource->file_type) }} · {{ $resource->human_size }} · {{ number_format($resource->download_count) }} downloads</div>
                                </div>
                                <a href="{{ route('admin.resources.download', $resource) }}" class="btn btn-sm btn-light ms-auto" aria-label="Download current file"><i class="bi bi-download" aria-hidden="true"></i></a>
                            </div>
                        @endif
                        <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" data-file-info="#file-info"
                               accept=".{{ implode(',.', \App\Models\Resource::ALLOWED_EXTENSIONS) }}" @unless ($editing) required @endunless aria-describedby="file-help">
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text" id="file-info"></div>
                        <div class="form-text" id="file-help">PDF, Word, Excel, PowerPoint, JPG, PNG or ZIP — up to {{ \App\Support\UploadRules::DOCUMENT_MAX_KB / 1024 }} MB.</div>
                        <hr>
                        <x-admin.switch name="is_active" label="Show on website" :checked="$resource->is_active" />
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-actions mt-4 card">
            <a href="{{ route('admin.resources.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Upload' }}</button>
        </div>
    </form>
@endsection
