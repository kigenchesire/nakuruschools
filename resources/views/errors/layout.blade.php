@extends('layouts.app')

@section('content')
    <section class="error-page">
        <div class="container">
            <div class="error-code" aria-hidden="true">@yield('code')</div>
            <h1 class="h2 mt-3">@yield('heading')</h1>
            <p class="lead-lg mx-auto mb-4" style="max-width: 36rem">@yield('message')</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                @yield('actions')
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg"><i class="bi bi-house-door me-1" aria-hidden="true"></i> Back to Home</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">Contact Us</a>
            </div>
        </div>
    </section>
@endsection
