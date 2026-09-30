<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@hasSection('title')@yield('title') · @endif Admin · {{ $site->schoolName() }}</title>
    @if ($site->faviconUrl())
        <link rel="icon" href="{{ $site->faviconUrl() }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    @endif
    @vite(['resources/scss/admin.scss', 'resources/js/admin.js'])
    @stack('styles')
</head>
<body>
    <x-admin.sidebar />

    <div class="admin-main">
        <x-admin.navbar />

        <main class="admin-content" id="main">
            <x-admin.alerts />
            @yield('content')
        </main>

        <footer class="px-4 py-3 small text-body-secondary border-top bg-white d-flex flex-wrap justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} {{ $site->schoolName() }} — Website Administration</span>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="text-body-secondary">View website <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></a>
        </footer>
    </div>

    {{-- Shared confirmation dialog for forms with data-confirm --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="mx-auto mb-3 rounded-circle d-grid tile-red" style="width:3.5rem;height:3.5rem;place-items:center">
                        <i class="bi bi-exclamation-triangle-fill fs-4" aria-hidden="true"></i>
                    </div>
                    <h2 class="h5 mb-2" id="confirmModalTitle" data-confirm-title>Are you sure?</h2>
                    <p class="text-body-secondary mb-0" data-confirm-message></p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" data-confirm-accept>Yes, delete</button>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
