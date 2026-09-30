@extends('layouts.admin')

@php $editing = $slider->exists; @endphp
@section('title', $editing ? 'Edit Slide' : 'Add Slide')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit Slide' : 'Add Slide'" :crumbs="['Sliders' => route('admin.sliders.index')]" />

    <form action="{{ $editing ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="form-section-title">Slide content</h2>
                        <x-admin.input name="title" label="Title" :value="$slider->title" required maxlength="150" help="The large headline, e.g. “Inspiring Excellence. Building Futures.”" />
                        <x-admin.input name="subtitle" label="Subtitle" :value="$slider->subtitle" maxlength="150" help="Small label shown above the title." />
                        <x-admin.textarea name="description" label="Description" :value="$slider->description" rows="3" maxlength="500" help="One or two short sentences (max 500 characters)." />
                        <div class="row">
                            <div class="col-md-5"><x-admin.input name="button_text" label="Button text" :value="$slider->button_text" maxlength="60" placeholder="e.g. Enquire Now" /></div>
                            <div class="col-md-7"><x-admin.input name="button_url" label="Button link" :value="$slider->button_url" maxlength="255" placeholder="/contact or https://…" prepend="bi-link-45deg" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <x-admin.image-field name="image" label="Slide image" :current="$slider->image_url" :required="! $editing"
                            help="Landscape photo, ideally 1920×1080 or larger. JPG, PNG or WebP up to 5 MB." />
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="form-section-title">Publishing</h2>
                        <x-admin.switch name="is_active" label="Show on website" :checked="$slider->is_active" />
                        <x-admin.input name="sort_order" type="number" label="Sort order" :value="$slider->sort_order" min="0" help="Lower numbers appear first." />
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky-actions mt-4 card">
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Create Slide' }}</button>
        </div>
    </form>
@endsection
