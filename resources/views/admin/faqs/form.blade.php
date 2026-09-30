@extends('layouts.admin')

@php $editing = $faq->exists; @endphp
@section('title', $editing ? 'Edit FAQ' : 'Add FAQ')

@section('content')
    <x-admin.breadcrumbs :title="$editing ? 'Edit FAQ' : 'Add FAQ'" :crumbs="['FAQs' => route('admin.faqs.index')]" />

    <form action="{{ $editing ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="post" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="card" style="max-width: 860px">
            <div class="card-body">
                <x-admin.input name="question" label="Question" :value="$faq->question" required maxlength="255" />
                <x-admin.textarea name="answer" label="Answer" :value="$faq->answer" rows="6" required maxlength="5000" help="Plain text. Line breaks are kept." />
                <div class="row align-items-end">
                    <div class="col-sm-4"><x-admin.input name="sort_order" type="number" label="Sort order" :value="$faq->sort_order" min="0" /></div>
                    <div class="col-sm-8"><x-admin.switch name="is_active" label="Show on website" :checked="$faq->is_active" /></div>
                </div>
            </div>
            <div class="sticky-actions">
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ $editing ? 'Save Changes' : 'Create FAQ' }}</button>
            </div>
        </div>
    </form>
@endsection
