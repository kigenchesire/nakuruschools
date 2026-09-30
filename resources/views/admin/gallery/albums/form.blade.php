@extends('layouts.admin')

@php $editing = $album->exists; @endphp
@section('title', $editing ? 'Edit Album' : 'New Album')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit Album' : 'New Album'" :crumbs="['Gallery' => route('admin.gallery.index'), 'Albums' => route('admin.gallery.albums.index')]" />

    <form action="{{ $editing ? route('admin.gallery.albums.update', $album) : route('admin.gallery.albums.store') }}" method="post" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="card" style="max-width: 720px">
            <div class="card-body">
                <x-admin.input name="name" label="Album name" :value="$album->name" required maxlength="100" placeholder="e.g. Sports" />
                <x-admin.textarea name="description" label="Description" :value="$album->description" rows="3" maxlength="500" help="Shown under the Gallery page heading when this album is selected." />
                <div class="row">
                    <div class="col-sm-6"><x-admin.input name="sort_order" type="number" label="Sort order" :value="$album->sort_order" min="0" /></div>
                </div>
                <x-admin.switch name="is_active" label="Show on website" :checked="$album->is_active" help="Hiding an album also hides its photos from the public gallery." />
            </div>
            <div class="sticky-actions">
                <a href="{{ route('admin.gallery.albums.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Create Album' }}</button>
            </div>
        </div>
    </form>
@endsection
