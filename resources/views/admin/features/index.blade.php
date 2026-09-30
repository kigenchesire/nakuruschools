@extends('layouts.admin')

@section('title', 'Why Choose Us')

@section('content')
    <x-admin.breadcrumbs title="Why Choose Us & Highlights" description="Homepage feature cards and key figures. Drag rows to reorder." />

    @foreach ($groups as $group => $label)
        @php $items = $features->get($group, collect()); @endphp
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>{{ $label }} <span class="text-body-secondary fw-normal small">({{ $items->count() }})</span></span>
                <a href="{{ route('admin.features.create', ['group' => $group]) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Add</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="width:40px"><span class="visually-hidden">Reorder</span></th>
                            <th scope="col" style="width:60px">Icon</th>
                            @if ($group === 'highlight')<th scope="col">Figure</th>@endif
                            <th scope="col">Title</th>
                            <th scope="col" class="d-none d-lg-table-cell">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody data-sortable="{{ route('admin.features.reorder') }}">
                        @forelse ($items as $item)
                            <tr data-id="{{ $item->id }}">
                                <td><span class="drag-handle" title="Drag to reorder"><i class="bi bi-grip-vertical" aria-hidden="true"></i></span></td>
                                <td><span class="tile-icon tile-navy d-inline-grid rounded-3" style="width:2.25rem;height:2.25rem;place-items:center"><i class="bi {{ $item->icon ?: 'bi-star' }}" aria-hidden="true"></i></span></td>
                                @if ($group === 'highlight')<td class="fw-bold">{{ $item->value }}</td>@endif
                                <td class="cell-title">{{ $item->title }}</td>
                                <td class="d-none d-lg-table-cell small text-body-secondary">{{ \Illuminate\Support\Str::limit($item->description, 90) }}</td>
                                <td><x-admin.status-badge :active="$item->is_active" /></td>
                                <td>
                                    <x-admin.row-actions :name="$item->title"
                                        :edit="route('admin.features.edit', $item)"
                                        :toggle="route('admin.features.toggle', $item)" :active="$item->is_active"
                                        :destroy="route('admin.features.destroy', $item)" />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="empty-row"><i class="bi bi-stars" aria-hidden="true"></i>Nothing here yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
