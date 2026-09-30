@php
    $schoolName = $site->schoolName();
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle . ' | ' . $schoolName : $schoolName . ' — ' . $site->setting('tagline');
    $description = trim($__env->yieldContent('meta_description')) ?: $site->setting('meta_description');
    $keywords = trim($__env->yieldContent('meta_keywords')) ?: $site->setting('meta_keywords');
    $ogImage = trim($__env->yieldContent('og_image')) ?: (\App\Support\Media::url($site->setting('og_image')) ?: $site->logoUrl());
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 300) }}">
    <meta name="keywords" content="{{ $keywords }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#0b2447">

    <meta property="og:site_name" content="{{ $schoolName }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $pageTitle ?: $schoolName }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 300) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">
    @stack('meta')

    @if ($site->faviconUrl())
        <link rel="icon" href="{{ $site->faviconUrl() }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    @endif

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <a href="#main" class="skip-link">Skip to main content</a>

    <x-frontend.navbar />

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    <x-frontend.footer />

    <button type="button" class="back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up" aria-hidden="true"></i></button>

    @stack('scripts')
</body>
</html>
