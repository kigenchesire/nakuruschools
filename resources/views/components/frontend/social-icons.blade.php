@props(['variant' => 'light', 'size' => null])
@php $links = $site->socialLinks(); @endphp
@if ($links->isNotEmpty())
    <ul {{ $attributes->merge(['class' => 'social-icons list-unstyled mb-0 social-' . $variant . ($size ? ' social-' . $size : '')]) }}>
        @foreach ($links as $link)
            <li>
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $site->schoolName() }} on {{ $link->label }}" title="{{ $link->label }}">
                    <i class="bi {{ $link->icon }}" aria-hidden="true"></i>
                </a>
            </li>
        @endforeach
    </ul>
@endif
