@extends('layouts.admin')

@section('title', 'Sliders')

@section('content')
    <x-admin.breadcrumbs title="Homepage Sliders" description="Slides shown in the hero carousel at the top of the homepage. Drag rows to change their order.">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Add Slide</a>
    </x-admin.breadcrumbs>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col" style="width:40px"><span class="visually-hidden">Reorder</span></th>
                        <th scope="col">Image</th>
                        <th scope="col">Title</th>
                        <th scope="col" class="d-none d-md-table-cell">Button</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody data-sortable="{{ route('admin.sliders.reorder') }}">
                    @forelse ($sliders as $slider)
                        <tr data-id="{{ $slider->id }}">
                            <td><span class="drag-handle" title="Drag to reorder"><i class="bi bi-grip-vertical" aria-hidden="true"></i></span></td>
                            <td>
                                @if ($slider->image_url)
                                    <img src="{{ $slider->image_url }}" alt="" class="table-thumb" loading="lazy">
                                @else
                                    <span class="table-thumb-placeholder"><i class="bi bi-image" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>
                                <div class="cell-title">{{ $slider->title }}</div>
                                @if ($slider->subtitle)<div class="cell-sub">{{ $slider->subtitle }}</div>@endif
                            </td>
                            <td class="d-none d-md-table-cell small">
                                @if ($slider->button_text)
                                    {{ $slider->button_text }}<div class="cell-sub text-truncate" style="max-width:180px">{{ $slider->button_url }}</div>
                                @else
                                    <span class="text-body-secondary">—</span>
                                @endif
                            </td>
                            <td><x-admin.status-badge :active="$slider->is_active" /></td>
                            <td>
                                <x-admin.row-actions :name="$slider->title"
                                    :edit="route('admin.sliders.edit', $slider)"
                                    :toggle="route('admin.sliders.toggle', $slider)" :active="$slider->is_active"
                                    :destroy="route('admin.sliders.destroy', $slider)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-row"><i class="bi bi-collection-play" aria-hidden="true"></i>No slides yet. <a href="{{ route('admin.sliders.create') }}">Add the first slide</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
