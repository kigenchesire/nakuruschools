{{-- Status shown with icon + text so it never relies on colour alone. --}}
@props(['active', 'on' => 'Active', 'off' => 'Inactive'])
@if ($active)
    <span class="status-badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle-fill" aria-hidden="true"></i>{{ $on }}</span>
@else
    <span class="status-badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-pause-circle-fill" aria-hidden="true"></i>{{ $off }}</span>
@endif
