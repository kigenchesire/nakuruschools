{{--
    Standard edit / toggle / delete buttons for a table row.
    Extra buttons can be passed in the default slot (rendered first).
--}}
@props([
    'edit' => null,
    'toggle' => null,
    'active' => null,
    'destroy' => null,
    'name' => 'this item',
])
<div class="d-flex justify-content-end gap-1 flex-nowrap">
    {{ $slot }}
    @if ($toggle)
        <form action="{{ $toggle }}" method="post">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="{{ $active ? 'Deactivate' : 'Activate' }}" aria-label="{{ $active ? 'Deactivate' : 'Activate' }} {{ $name }}">
                <i class="bi {{ $active ? 'bi-toggle-on text-success' : 'bi-toggle-off' }}" aria-hidden="true"></i>
            </button>
        </form>
    @endif
    @if ($edit)
        <a href="{{ $edit }}" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Edit" aria-label="Edit {{ $name }}"><i class="bi bi-pencil-square" aria-hidden="true"></i></a>
    @endif
    @if ($destroy)
        <form action="{{ $destroy }}" method="post" data-confirm="“{{ \Illuminate\Support\Str::limit($name, 60) }}” will be permanently deleted. This cannot be undone.">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-light text-danger" data-bs-toggle="tooltip" title="Delete" aria-label="Delete {{ $name }}"><i class="bi bi-trash3" aria-hidden="true"></i></button>
        </form>
    @endif
</div>
