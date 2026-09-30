@extends('layouts.admin')

@section('title', 'Gallery Albums')

@section('content')
    <x-admin.breadcrumbs title="Gallery Albums" :crumbs="['Gallery' => route('admin.gallery.index')]" description="Albums group photos on the Gallery page. Drag rows to reorder the filter tabs.">
        <a href="{{ route('admin.gallery.albums.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> New Album</a>
    </x-admin.breadcrumbs>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col" style="width:40px"><span class="visually-hidden">Reorder</span></th>
                        <th scope="col">Album</th>
                        <th scope="col">Images</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody data-sortable="{{ route('admin.gallery.albums.reorder') }}">
                    @forelse ($albums as $album)
                        <tr data-id="{{ $album->id }}">
                            <td><span class="drag-handle" title="Drag to reorder"><i class="bi bi-grip-vertical" aria-hidden="true"></i></span></td>
                            <td>
                                <div class="cell-title">{{ $album->name }}</div>
                                @if ($album->description)<div class="cell-sub">{{ \Illuminate\Support\Str::limit($album->description, 80) }}</div>@endif
                            </td>
                            <td><a href="{{ route('admin.gallery.index', ['album' => $album->id]) }}">{{ $album->images_count }} {{ \Illuminate\Support\Str::plural('image', $album->images_count) }}</a></td>
                            <td><x-admin.status-badge :active="$album->is_active" /></td>
                            <td>
                                <x-admin.row-actions :name="$album->name" :edit="route('admin.gallery.albums.edit', $album)" :destroy="route('admin.gallery.albums.destroy', $album)">
                                    <a href="{{ route('admin.gallery.create', ['album' => $album->id]) }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Upload to album" aria-label="Upload images to {{ $album->name }}"><i class="bi bi-cloud-upload" aria-hidden="true"></i></a>
                                </x-admin.row-actions>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row"><i class="bi bi-folder" aria-hidden="true"></i>No albums yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
