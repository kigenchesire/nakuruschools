@props(['title', 'subtitle' => null, 'crumbs' => []])
<section class="page-header brand-pattern">
    <div class="container position-relative">
        <nav aria-label="Breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @foreach ($crumbs as $label => $url)
                    <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($title, 60) }}</li>
            </ol>
        </nav>
        <h1>{{ $title }}</h1>
        @if ($subtitle)
            <p>{{ $subtitle }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
