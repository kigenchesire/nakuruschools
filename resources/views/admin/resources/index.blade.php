@extends('layouts.admin')

@section('title', 'Resources')

@section('content')
    <x-admin.breadcrumbs title="Resources & Downloads" description="Documents visitors can download from the Resources page. Files are stored privately and served through the website.">
        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary"><i class="bi bi-cloud-upload me-1" aria-hidden="true"></i> Upload Resource</a>
    </x-admin.breadcrumbs>

    <div class="card">
        <div class="card-header">
            <form method="get" class="row g-2 align-items-center" role="search">
                <div class="col-md-6 col-lg-5">
                    <label for="q" class="visually-hidden">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                        <input type="search" name="q" id="q" value="{{ $search }}" class="form-control" placeholder="Search resources…">
                    </div>
                </div>
                <div class="col-8 col-md-3">
                    <label for="category" class="visually-hidden">Category</label>
                    <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 col-md-auto d-flex gap-2">
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                    @if ($search || $category)<a href="{{ route('admin.resources.index') }}" class="btn btn-link px-1">Reset</a>@endif
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Resource</th>
                        <th scope="col" class="d-none d-md-table-cell">Category</th>
                        <th scope="col" class="d-none d-lg-table-cell">File</th>
                        <th scope="col" class="d-none d-sm-table-cell">Downloads</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resources as $resource)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="tile-icon tile-red d-grid rounded-3 flex-shrink-0" style="width:2.5rem;height:2.5rem;place-items:center"><i class="bi {{ $resource->icon }}" aria-hidden="true"></i></span>
                                    <div class="min-w-0">
                                        <div class="cell-title">{{ $resource->title }}</div>
                                        <div class="cell-sub">Added {{ $resource->created_at->format('j M Y') }}{{ $resource->creator ? ' by ' . $resource->creator->full_name : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell small">{{ $resource->category ?: '—' }}</td>
                            <td class="d-none d-lg-table-cell small text-nowrap">{{ strtoupper($resource->file_type) }} · {{ $resource->human_size }}</td>
                            <td class="d-none d-sm-table-cell">{{ number_format($resource->download_count) }}</td>
                            <td><x-admin.status-badge :active="$resource->is_active" on="Visible" off="Hidden" /></td>
                            <td>
                                <x-admin.row-actions :name="$resource->title"
                                    :edit="route('admin.resources.edit', $resource)"
                                    :toggle="route('admin.resources.toggle', $resource)" :active="$resource->is_active"
                                    :destroy="route('admin.resources.destroy', $resource)">
                                    <a href="{{ route('admin.resources.download', $resource) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Download" aria-label="Download {{ $resource->title }}"><i class="bi bi-download" aria-hidden="true"></i></a>
                                </x-admin.row-actions>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-row"><i class="bi bi-folder2-open" aria-hidden="true"></i>{{ $search || $category ? 'No resources match your filters.' : 'No resources uploaded yet.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($resources->hasPages())
            <div class="card-footer bg-white">{{ $resources->links() }}</div>
        @endif
    </div>
@endsection
