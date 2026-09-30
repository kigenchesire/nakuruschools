@extends('layouts.admin')

@php $editing = $user->exists; @endphp
@section('title', $editing ? 'Edit User' : 'Add User')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit: ' . $user->full_name : 'Add User'" :crumbs="['Users' => route('admin.users.index')]" />

    <form action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}" method="post" novalidate autocomplete="off">
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="form-section-title">Profile</h2>
                        <div class="row">
                            <div class="col-md-6"><x-admin.input name="first_name" label="First name" :value="$user->first_name" required maxlength="80" /></div>
                            <div class="col-md-6"><x-admin.input name="last_name" label="Last name" :value="$user->last_name" required maxlength="80" /></div>
                        </div>
                        <x-admin.input name="email" type="email" label="Email address" :value="$user->email" required maxlength="150" prepend="bi-envelope" help="Used to sign in." />
                        <x-admin.input name="phone" type="tel" label="Phone" :value="$user->phone" maxlength="30" prepend="bi-telephone" />
                        <div class="mb-3">
                            <label for="status" class="form-label required">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" @disabled($editing && auth()->user()->is($user))>
                                <option value="active" @selected(old('status', $user->status) === 'active')>Active — can sign in</option>
                                <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive — access blocked</option>
                            </select>
                            @if ($editing && auth()->user()->is($user))
                                <input type="hidden" name="status" value="active">
                                <div class="form-text">You cannot deactivate your own account.</div>
                            @endif
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="form-section-title">{{ $editing ? 'Reset password' : 'Password' }}</h2>
                        @if ($editing)
                            <p class="small text-body-secondary">Leave blank to keep the current password.</p>
                        @endif
                        <x-admin.input name="password" type="password" :label="$editing ? 'New password' : 'Password'" :required="! $editing" autocomplete="new-password"
                            help="At least 8 characters, including letters and numbers." />
                        <x-admin.input name="password_confirmation" type="password" label="Confirm password" :required="! $editing" autocomplete="new-password" />
                        <div class="alert alert-light border small mb-0"><i class="bi bi-shield-lock me-1" aria-hidden="true"></i> Passwords are stored securely (hashed) and can never be viewed.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-actions mt-4 card">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Create User' }}</button>
        </div>
    </form>
@endsection
