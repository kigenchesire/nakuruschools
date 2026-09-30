@extends('layouts.admin')

@section('title', 'FAQs')

@section('content')
    <x-admin.breadcrumbs title="Frequently Asked Questions" description="The first five active questions also appear on the homepage. Drag rows to reorder.">
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Add FAQ</a>
    </x-admin.breadcrumbs>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col" style="width:40px"><span class="visually-hidden">Reorder</span></th>
                        <th scope="col">Question</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody data-sortable="{{ route('admin.faqs.reorder') }}">
                    @forelse ($faqs as $faq)
                        <tr data-id="{{ $faq->id }}">
                            <td><span class="drag-handle" title="Drag to reorder"><i class="bi bi-grip-vertical" aria-hidden="true"></i></span></td>
                            <td>
                                <div class="cell-title">{{ $faq->question }}</div>
                                <div class="cell-sub">{{ \Illuminate\Support\Str::limit($faq->answer, 110) }}</div>
                            </td>
                            <td><x-admin.status-badge :active="$faq->is_active" /></td>
                            <td>
                                <x-admin.row-actions :name="$faq->question"
                                    :edit="route('admin.faqs.edit', $faq)"
                                    :toggle="route('admin.faqs.toggle', $faq)" :active="$faq->is_active"
                                    :destroy="route('admin.faqs.destroy', $faq)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-row"><i class="bi bi-question-circle" aria-hidden="true"></i>No FAQs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
