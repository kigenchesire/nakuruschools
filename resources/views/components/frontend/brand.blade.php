@props(['tagline' => true])
<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'brand']) }} aria-label="{{ $site->schoolName() }} — home">
    @if ($site->logoUrl())
        <img src="{{ $site->logoUrl() }}" alt="{{ $site->schoolName() }} logo" width="54" height="54">
    @else
        <x-frontend.logo-mark :label="$site->schoolName()" />
    @endif
    <span>
        <span class="brand-name">{{ $site->schoolName() }}</span>
        @if ($tagline && $site->setting('tagline'))
            <span class="brand-tagline d-none d-sm-block">{{ \Illuminate\Support\Str::limit($site->setting('tagline'), 42) }}</span>
        @endif
    </span>
</a>
