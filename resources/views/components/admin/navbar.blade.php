@php $user = auth()->user(); @endphp
<header class="admin-topbar">
    <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Open menu">
        <i class="bi bi-list fs-4" aria-hidden="true"></i>
    </button>

    <div class="fw-semibold text-body-secondary small d-none d-md-block">
        {{ now()->format('l, j F Y') }}
    </div>

    <div class="ms-auto d-flex align-items-center gap-2">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View website" aria-label="View website">
            <i class="bi bi-globe2" aria-hidden="true"></i>
        </a>
        <a href="{{ route('admin.enquiries.index', ['status' => 'unread']) }}" class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unread enquiries" aria-label="Unread enquiries">
            <i class="bi bi-bell" aria-hidden="true"></i>
            @if ($unreadEnquiries > 0)
                <span class="dot"></span><span class="visually-hidden">({{ $unreadEnquiries }} unread)</span>
            @endif
        </a>

        <div class="dropdown">
            <button class="btn d-flex align-items-center gap-2 px-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar" aria-hidden="true">{{ $user->initials }}</span>
                <span class="d-none d-sm-block text-start lh-sm">
                    <span class="d-block fw-semibold small">{{ $user->full_name }}</span>
                    <span class="d-block text-body-secondary" style="font-size:.72rem">Administrator</span>
                </span>
                <i class="bi bi-chevron-down small text-body-secondary" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                <li class="px-3 py-2 small text-body-secondary d-sm-none">{{ $user->email }}</li>
                <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person me-2" aria-hidden="true"></i>My Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings.edit') }}"><i class="bi bi-gear me-2" aria-hidden="true"></i>Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
