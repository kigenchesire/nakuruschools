@extends('layouts.app')

@section('title', 'Resources & Downloads')
@section('meta_description', 'Download admission forms, fee structures, school calendars, brochures and policies from ' . $site->schoolName() . '.')

@section('content')
    <x-frontend.page-header title="Resources & Downloads" subtitle="Admission forms, fee structures, calendars, brochures and policies — all in one place." />

    <section class="section">
        <div class="container">
            @if ($categories->isNotEmpty())
                <nav class="filter-pills mb-5" aria-label="Filter resources by category">
                    <a href="{{ route('resources.index') }}" class="{{ ! $category ? 'active' : '' }}" @if (! $category) aria-current="page" @endif>All</a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('resources.index', ['category' => $cat]) }}" class="{{ $category === $cat ? 'active' : '' }}" @if ($category === $cat) aria-current="page" @endif>{{ $cat }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($resources->isNotEmpty())
                <div class="row g-4">
                    @foreach ($resources as $resource)
                        <div class="col-md-6 col-xl-4 reveal"><x-frontend.resource-card :resource="$resource" /></div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $resources->links() }}</div>
            @else
                <div class="empty-state">
                    <i class="bi bi-folder2-open" aria-hidden="true"></i>
                    <h2 class="h5">No documents available</h2>
                    <p class="mb-0">Please check back soon, or <a href="{{ route('contact') }}">contact the school office</a> for the document you need.</p>
                </div>
            @endif
        </div>
    </section>

    <x-frontend.cta title="Can’t find what you need?" text="Our school office is happy to help with forms, fee information and any other documents." button-text="Contact the Office" :button-url="route('contact')" />
@endsection
