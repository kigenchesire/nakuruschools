@props(['resource'])
<article class="resource-card">
    <div class="file-icon" aria-hidden="true"><i class="bi {{ $resource->icon }}"></i></div>
    <div class="flex-grow-1 min-w-0">
        <h3>{{ $resource->title }}</h3>
        @if ($resource->description)
            <p>{{ \Illuminate\Support\Str::limit($resource->description, 120) }}</p>
        @endif
        <div class="file-meta">
            <span><i class="bi bi-file-earmark" aria-hidden="true"></i><span class="visually-hidden">File type:</span>{{ strtoupper($resource->file_type) }}</span>
            <span><i class="bi bi-hdd" aria-hidden="true"></i><span class="visually-hidden">Size:</span>{{ $resource->human_size }}</span>
            @if ($resource->category)
                <span><i class="bi bi-tag" aria-hidden="true"></i>{{ $resource->category }}</span>
            @endif
        </div>
        <a href="{{ route('resources.download', $resource) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2" rel="nofollow">
            <i class="bi bi-download" aria-hidden="true"></i> Download <span class="visually-hidden">{{ $resource->title }}</span>
        </a>
    </div>
</article>
