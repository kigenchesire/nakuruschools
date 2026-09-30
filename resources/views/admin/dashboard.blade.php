@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <h1>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->first_name }} 👋</h1>
            <p>Here’s what’s happening on the {{ $site->schoolName() }} website.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> New Article</a>
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-outline-primary"><i class="bi bi-cloud-upload me-1" aria-hidden="true"></i> Upload Photos</a>
        </div>
    </div>

    @if ($unread > 0)
        <div class="alert alert-warning d-flex flex-wrap align-items-center gap-3 shadow-sm">
            <i class="bi bi-envelope-exclamation-fill fs-4" aria-hidden="true"></i>
            <div class="flex-grow-1">You have <strong>{{ $unread }} unread {{ \Illuminate\Support\Str::plural('enquiry', $unread) }}</strong> from the contact form.</div>
            <a href="{{ route('admin.enquiries.index', ['status' => 'unread']) }}" class="btn btn-sm btn-dark">Review now</a>
        </div>
    @endif

    <div class="row g-3 mb-4">
        @foreach ($stats as $stat)
            <div class="col-sm-6 col-xl-3 {{ $loop->last ? 'col-xl-3' : '' }}">
                <a href="{{ route($stat['route']) }}" class="card stat-tile text-decoration-none h-100">
                    <span class="tile-icon tile-{{ $stat['color'] }}" aria-hidden="true"><i class="bi {{ $stat['icon'] }}"></i></span>
                    <span>
                        <span class="tile-value d-block">{{ number_format($stat['value']) }}</span>
                        <span class="tile-label">{{ $stat['label'] }}</span>
                    </span>
                </a>
            </div>
        @endforeach
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('admin.settings.edit') }}" class="card stat-tile text-decoration-none h-100 border-dashed" style="border-style: dashed">
                <span class="tile-icon tile-gold" aria-hidden="true"><i class="bi bi-palette"></i></span>
                <span>
                    <span class="fw-bold d-block">Branding & SEO</span>
                    <span class="tile-label">Logo, homepage text, meta tags</span>
                </span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-bar-chart me-2 text-body-secondary" aria-hidden="true"></i>Enquiries — last 6 months</span>
                    <a href="{{ route('admin.enquiries.index') }}" class="small">View all</a>
                </div>
                <div class="card-body">
                    <div style="height: 280px">
                        <canvas data-chart='@json($enquiryChart + ['label' => 'Enquiries'])' role="img"
                                aria-label="Bar chart of enquiries per month: {{ collect($enquiryChart['labels'])->zip($enquiryChart['data'])->map(fn ($p) => $p[0] . ' ' . $p[1])->implode(', ') }}"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-envelope me-2 text-body-secondary" aria-hidden="true"></i>Recent enquiries</span>
                    <a href="{{ route('admin.enquiries.index') }}" class="small">All</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($recentEnquiries as $enquiry)
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex justify-content-between gap-2">
                                <strong class="small text-truncate">{{ $enquiry->name }}</strong>
                                <small class="text-body-secondary flex-shrink-0">{{ $enquiry->created_at->diffForHumans(short: true) }}</small>
                            </div>
                            <div class="small text-body-secondary text-truncate">{{ $enquiry->subject }}</div>
                            @if ($enquiry->status === 'unread')
                                <span class="badge text-bg-danger mt-1"><i class="bi bi-envelope-fill me-1" aria-hidden="true"></i>New</span>
                            @endif
                        </a>
                    @empty
                        <div class="p-4 text-center text-body-secondary small">No enquiries yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-2 text-body-secondary" aria-hidden="true"></i>Recent news activity</span>
                    <a href="{{ route('admin.news.index') }}" class="small">Manage news</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th scope="col">Article</th><th scope="col">Status</th><th scope="col">Updated</th></tr></thead>
                        <tbody>
                            @forelse ($recentNews as $article)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.news.edit', $article) }}" class="cell-title d-block text-truncate">{{ $article->title }}</a>
                                        <span class="cell-sub">by {{ $article->author_name }}</span>
                                    </td>
                                    <td><x-admin.status-badge :active="$article->isPublished()" on="Published" off="Draft" /></td>
                                    <td class="text-nowrap small text-body-secondary">{{ $article->updated_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-row"><i class="bi bi-newspaper" aria-hidden="true"></i>No articles yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-download me-2 text-body-secondary" aria-hidden="true"></i>Most downloaded resources</div>
                <ul class="list-group list-group-flush">
                    @forelse ($topResources as $resource)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-2 py-3">
                            <span class="small text-truncate"><i class="bi {{ $resource->icon }} text-danger me-2" aria-hidden="true"></i>{{ $resource->title }}</span>
                            <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ number_format($resource->download_count) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-body-secondary small p-4">No resources uploaded yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
