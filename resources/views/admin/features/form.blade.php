@extends('layouts.admin')

@php $editing = $feature->exists; @endphp
@section('title', $editing ? 'Edit Item' : 'Add Item')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit: ' . $feature->title : 'Add Item'" :crumbs="['Why Choose Us' => route('admin.features.index')]" />

    <form action="{{ $editing ? route('admin.features.update', $feature) : route('admin.features.store') }}" method="post" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="card" style="max-width: 820px">
            <div class="card-body">
                <div class="mb-3">
                    <label for="group" class="form-label required">Section</label>
                    <select name="group" id="group" class="form-select @error('group') is-invalid @enderror">
                        @foreach (\App\Models\Feature::GROUPS as $value => $label)
                            <option value="{{ $value }}" @selected(old('group', $feature->group) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">“Why Choose Us” items appear as cards; “Key Highlights” appear as figures (e.g. 1,200+ Learners).</div>
                </div>
                <div class="row">
                    <div class="col-md-8"><x-admin.input name="title" label="Title" :value="$feature->title" required maxlength="100" /></div>
                    <div class="col-md-4"><x-admin.input name="value" label="Figure (highlights only)" :value="$feature->value" maxlength="30" placeholder="e.g. 1,200+" /></div>
                </div>
                <x-admin.textarea name="description" label="Description" :value="$feature->description" rows="3" maxlength="400" />
                <div class="row">
                    <div class="col-md-6">
                        <x-admin.input name="icon" label="Icon" :value="$feature->icon" maxlength="60" placeholder="bi-mortarboard" help="Bootstrap Icons class name, see icons.getbootstrap.com" />
                    </div>
                    <div class="col-md-6"><x-admin.input name="sort_order" type="number" label="Sort order" :value="$feature->sort_order" min="0" /></div>
                </div>
                <x-admin.switch name="is_active" label="Show on website" :checked="$feature->is_active" />
            </div>
            <div class="sticky-actions">
                <a href="{{ route('admin.features.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Create' }}</button>
            </div>
        </div>
    </form>
@endsection
