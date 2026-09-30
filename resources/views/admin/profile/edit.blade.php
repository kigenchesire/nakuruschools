@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
    <x-admin.breadcrumbs title="My Profile" description="Update your details and password." />

    <div class="row g-4">
        <div class="col-lg-7">
            <form action="{{ route('admin.profile.update') }}" method="post" class="card h-100" novalidate>
                @csrf @method('PUT')
                <div class="card-header">Profile details</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><x-admin.input name="first_name" label="First name" :value="$user->first_name" required maxlength="80" /></div>
                        <div class="col-md-6"><x-admin.input name="last_name" label="Last name" :value="$user->last_name" required maxlength="80" /></div>
                    </div>
                    <x-admin.input name="email" type="email" label="Email address" :value="$user->email" required maxlength="150" prepend="bi-envelope" />
                    <x-admin.input name="phone" type="tel" label="Phone" :value="$user->phone" maxlength="30" prepend="bi-telephone" />
                </div>
                <div class="sticky-actions"><button class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> Save Profile</button></div>
            </form>
        </div>

        <div class="col-lg-5">
            <form action="{{ route('admin.profile.password') }}" method="post" class="card h-100" novalidate>
                @csrf @method('PUT')
                <div class="card-header">Change password</div>
                <div class="card-body">
                    @foreach (['current_password' => 'Current password', 'password' => 'New password', 'password_confirmation' => 'Confirm new password'] as $field => $label)
                        <div class="mb-3">
                            <label for="{{ $field }}" class="form-label required">{{ $label }}</label>
                            <input type="password" name="{{ $field }}" id="{{ $field }}" class="form-control @error($field, 'password') is-invalid @enderror"
                                   autocomplete="{{ $field === 'current_password' ? 'current-password' : 'new-password' }}" required>
                            @error($field, 'password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if ($field === 'password')<div class="form-text">At least 8 characters with letters and numbers.</div>@endif
                        </div>
                    @endforeach
                    <p class="small text-body-secondary mb-0"><i class="bi bi-info-circle me-1" aria-hidden="true"></i>Changing your password signs you out on other devices.</p>
                </div>
                <div class="sticky-actions"><button class="btn btn-warning"><i class="bi bi-key me-1" aria-hidden="true"></i> Update Password</button></div>
            </form>
        </div>
    </div>
@endsection
