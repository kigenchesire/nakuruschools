@extends('layouts.admin')

@php
    $editing = $section->exists;
    $key = $section->key;
    $summaryHelp = match ($key) {
        'introduction' => 'Short introduction shown on the homepage About preview.',
        'mission', 'vision' => 'The statement itself, shown on the homepage and About page.',
        'core_values' => 'Enter one value per line, e.g. Integrity, Excellence, Respect.',
        default => 'Optional short summary.',
    };
    $showEditor = $key !== 'core_values';
@endphp

@section('title', $editing ? 'Edit ' . $section->title : 'Add Section')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit: ' . $section->title : 'Add About Section'" :crumbs="['About Us' => route('admin.about.index')]" />

    <form action="{{ $editing ? route('admin.about.update', $section) : route('admin.about.store') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$section->title" required maxlength="150" />
                        <x-admin.input name="subtitle" label="Subtitle" :value="$section->subtitle" maxlength="200" help="Optional short line shown near the title." />
                        <x-admin.textarea name="summary" :label="$key === 'core_values' ? 'Core values (one per line)' : 'Summary'" :value="$section->summary" :rows="$key === 'core_values' ? 7 : 3" maxlength="2000" :help="$summaryHelp" />
                        @if ($showEditor)
                            <x-admin.textarea name="content" label="Full content" :value="$section->content" editor rows="12"
                                help="Shown on the About page. Use headings, lists and links to structure longer text." />
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <x-admin.image-field name="image" label="Image" :current="$section->image_url" removable />
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="form-section-title">Display</h2>
                        <x-admin.switch name="is_active" label="Show on website" :checked="$section->is_active" />
                        <x-admin.input name="icon" label="Icon" :value="$section->icon" maxlength="60" placeholder="bi-bullseye" help="Optional Bootstrap Icons class (icons.getbootstrap.com)." />
                        <x-admin.input name="sort_order" type="number" label="Sort order" :value="$section->sort_order" min="0" />
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky-actions mt-4 card">
            <a href="{{ route('admin.about.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Create Section' }}</button>
        </div>
    </form>
@endsection
