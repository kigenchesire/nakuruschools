@extends('layouts.admin')

@section('title', 'Contact Enquiries')

@section('content')
    <x-admin.breadcrumbs title="Contact Enquiries" description="Messages sent through the website contact form." />

    <ul class="nav nav-pills gap-2 mb-3 flex-nowrap overflow-auto pb-1">
        <li class="nav-item">
            <a class="nav-link {{ ! $status ? 'active' : 'bg-white' }}" href="{{ route('admin.enquiries.index') }}">Inbox</a>
        </li>
        @foreach (\App\Models\ContactEnquiry::STATUSES as $key => [$label, $color, $icon])
            <li class="nav-item">
                <a class="nav-link text-nowrap {{ $status === $key ? 'active' : 'bg-white' }}" href="{{ route('admin.enquiries.index', ['status' => $key]) }}">
                    <i class="bi {{ $icon }} me-1" aria-hidden="true"></i>{{ $label }}
                    <span class="badge rounded-pill {{ $status === $key ? 'bg-light text-dark' : 'bg-secondary-subtle text-secondary-emphasis' }} ms-1">{{ $counts[$key] ?? 0 }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="card">
        <div class="card-header">
            <form method="get" class="d-flex gap-2" role="search" style="max-width: 480px">
                @if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
                <label for="q" class="visually-hidden">Search enquiries</label>
                <input type="search" name="q" id="q" value="{{ $search }}" class="form-control" placeholder="Search name, email or subject…">
                <button class="btn btn-outline-primary" type="submit" aria-label="Search"><i class="bi bi-search" aria-hidden="true"></i></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Subject</th>
                        <th scope="col" class="d-none d-lg-table-cell">Phone</th>
                        <th scope="col" class="d-none d-md-table-cell">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enquiries as $enquiry)
                        <tr class="{{ $enquiry->status === 'unread' ? 'fw-semibold' : '' }}">
                            <td>
                                <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="d-block text-body">{{ $enquiry->name }}</a>
                                <span class="cell-sub fw-normal">{{ $enquiry->email }}</span>
                            </td>
                            <td><a href="{{ route('admin.enquiries.show', $enquiry) }}" class="text-body">{{ \Illuminate\Support\Str::limit($enquiry->subject, 50) }}</a></td>
                            <td class="d-none d-lg-table-cell small fw-normal">{{ $enquiry->phone ?: '—' }}</td>
                            <td class="d-none d-md-table-cell small text-nowrap fw-normal" title="{{ $enquiry->created_at->format('j M Y, g:i a') }}">{{ $enquiry->created_at->format('j M Y') }}</td>
                            <td><span class="status-badge bg-{{ $enquiry->status_color }}-subtle text-{{ $enquiry->status_color }}-emphasis"><i class="bi {{ $enquiry->status_icon }}" aria-hidden="true"></i>{{ $enquiry->status_label }}</span></td>
                            <td>
                                <x-admin.row-actions :name="'the enquiry from ' . $enquiry->name" :destroy="route('admin.enquiries.destroy', $enquiry)">
                                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Open" aria-label="Open enquiry from {{ $enquiry->name }}"><i class="bi bi-envelope-open" aria-hidden="true"></i></a>
                                </x-admin.row-actions>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-row"><i class="bi bi-inbox" aria-hidden="true"></i>No enquiries here.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($enquiries->hasPages())
            <div class="card-footer bg-white">{{ $enquiries->links() }}</div>
        @endif
    </div>
@endsection
