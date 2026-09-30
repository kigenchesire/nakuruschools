@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <x-admin.breadcrumbs title="Users" description="People who can sign in and manage the website.">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1" aria-hidden="true"></i> Add User</a>
    </x-admin.breadcrumbs>

    <div class="card">
        <div class="card-header">
            <form method="get" class="row g-2" role="search">
                <div class="col-md-6 col-lg-5">
                    <label for="q" class="visually-hidden">Search users</label>
                    <input type="search" name="q" id="q" value="{{ $search }}" class="form-control" placeholder="Search name, email or phone…">
                </div>
                <div class="col-8 col-md-3">
                    <label for="status" class="visually-hidden">Status</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All users</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-4 col-md-auto"><button class="btn btn-outline-primary w-100">Search</button></div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Full Name</th>
                        <th scope="col" class="d-none d-md-table-cell">Email</th>
                        <th scope="col" class="d-none d-lg-table-cell">Phone</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="d-none d-lg-table-cell">Created</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php $isMe = auth()->user()->is($user); @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar d-grid rounded-circle bg-primary text-warning fw-bold small flex-shrink-0" style="width:2.25rem;height:2.25rem;place-items:center" aria-hidden="true">{{ $user->initials }}</span>
                                    <div>
                                        <div class="cell-title">{{ $user->full_name }} @if ($isMe)<span class="badge bg-warning-subtle text-warning-emphasis ms-1">You</span>@endif</div>
                                        <div class="cell-sub d-md-none">{{ $user->email }}</div>
                                        <div class="cell-sub">Last sign-in: {{ $user->last_login_at?->diffForHumans() ?? 'never' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell small">{{ $user->email }}</td>
                            <td class="d-none d-lg-table-cell small">{{ $user->phone ?: '—' }}</td>
                            <td><x-admin.status-badge :active="$user->isActive()" /></td>
                            <td class="d-none d-lg-table-cell small text-nowrap">{{ $user->created_at->format('j M Y') }}</td>
                            <td>
                                <x-admin.row-actions :name="$user->full_name"
                                    :edit="route('admin.users.edit', $user)"
                                    :toggle="$isMe ? null : route('admin.users.toggle', $user)" :active="$user->isActive()"
                                    :destroy="$isMe ? null : route('admin.users.destroy', $user)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-row"><i class="bi bi-people" aria-hidden="true"></i>No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-footer bg-white">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
