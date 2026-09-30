@extends('layouts.admin')

@section('title', 'About Us')

@section('content')
    <x-admin.breadcrumbs title="About Us" description="Content for the About page and the About preview on the homepage.">
        <a href="{{ route('about') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary"><i class="bi bi-eye me-1" aria-hidden="true"></i> View Page</a>
        <a href="{{ route('admin.about.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Add Section</a>
    </x-admin.breadcrumbs>

    <div class="row g-3">
        @foreach ($sections as $section)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge {{ $section->isFixed() ? 'bg-primary-subtle text-primary-emphasis' : 'bg-warning-subtle text-warning-emphasis' }}">
                                {{ $section->isFixed() ? \App\Models\AboutSection::FIXED_KEYS[$section->key] : 'Additional section' }}
                            </span>
                            <x-admin.status-badge :active="$section->is_active" on="Visible" off="Hidden" />
                        </div>
                        <h2 class="h5 mb-2">{{ $section->title }}</h2>
                        <p class="text-body-secondary small flex-grow-1">
                            {{ \Illuminate\Support\Str::limit($section->summary ?: strip_tags($section->content), 140) ?: 'No content yet.' }}
                        </p>
                        <div class="d-flex gap-2 align-items-center">
                            @if ($section->image_url)
                                <img src="{{ $section->image_url }}" alt="" class="table-thumb me-auto">
                            @else
                                <span class="me-auto small text-body-secondary"><i class="bi bi-image me-1" aria-hidden="true"></i>No image</span>
                            @endif
                            <a href="{{ route('admin.about.edit', $section) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square me-1" aria-hidden="true"></i>Edit</a>
                            @unless ($section->isFixed())
                                <form action="{{ route('admin.about.destroy', $section) }}" method="post" data-confirm="The section “{{ $section->title }}” will be permanently deleted.">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger" aria-label="Delete {{ $section->title }}"><i class="bi bi-trash3" aria-hidden="true"></i></button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
