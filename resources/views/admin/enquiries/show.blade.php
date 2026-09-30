@extends('layouts.admin')

@section('title', 'Enquiry from ' . $enquiry->name)

@section('content')
    <x-admin.breadcrumbs :title="$enquiry->subject" :crumbs="['Enquiries' => route('admin.enquiries.index')]" />

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span><i class="bi bi-chat-left-text me-2 text-body-secondary" aria-hidden="true"></i>Message</span>
                    <small class="text-body-secondary fw-normal">Received {{ $enquiry->created_at->format('l, j F Y \a\t g:i a') }}</small>
                </div>
                <div class="card-body">
                    <div class="message-body">{{ $enquiry->message }}</div>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: ' . $enquiry->subject) }}" class="btn btn-primary"><i class="bi bi-reply-fill me-1" aria-hidden="true"></i> Reply by Email</a>
                        @if ($enquiry->phone)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $enquiry->phone) }}" class="btn btn-outline-primary"><i class="bi bi-telephone me-1" aria-hidden="true"></i> Call</a>
                            <a href="https://wa.me/{{ preg_replace('/^0/', '254', preg_replace('/[^0-9]/', '', $enquiry->phone)) }}" target="_blank" rel="noopener" class="btn btn-outline-success"><i class="bi bi-whatsapp me-1" aria-hidden="true"></i> WhatsApp</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">Sender</div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex gap-2"><i class="bi bi-person text-body-secondary" aria-hidden="true"></i><span class="fw-semibold">{{ $enquiry->name }}</span></li>
                    <li class="list-group-item d-flex gap-2"><i class="bi bi-envelope text-body-secondary" aria-hidden="true"></i><a href="mailto:{{ $enquiry->email }}" class="text-break">{{ $enquiry->email }}</a></li>
                    <li class="list-group-item d-flex gap-2"><i class="bi bi-telephone text-body-secondary" aria-hidden="true"></i>{{ $enquiry->phone ?: 'Not provided' }}</li>
                    @if ($enquiry->ip_address)
                        <li class="list-group-item d-flex gap-2 text-body-secondary"><i class="bi bi-globe" aria-hidden="true"></i>IP {{ $enquiry->ip_address }}</li>
                    @endif
                </ul>
            </div>

            <div class="card">
                <div class="card-header">Status</div>
                <div class="card-body">
                    <p class="mb-3">Current: <span class="status-badge bg-{{ $enquiry->status_color }}-subtle text-{{ $enquiry->status_color }}-emphasis"><i class="bi {{ $enquiry->status_icon }}" aria-hidden="true"></i>{{ $enquiry->status_label }}</span></p>
                    <div class="d-grid gap-2">
                        @foreach (\App\Models\ContactEnquiry::STATUSES as $key => [$label, $color, $icon])
                            @continue($key === $enquiry->status)
                            <form action="{{ route('admin.enquiries.status', $enquiry) }}" method="post">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $key }}">
                                <button class="btn btn-outline-{{ $color === 'dark' ? 'secondary' : $color }} w-100 text-start"><i class="bi {{ $icon }} me-2" aria-hidden="true"></i>Mark as {{ strtolower($label) }}</button>
                            </form>
                        @endforeach
                    </div>
                    <hr>
                    <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="post" data-confirm="This enquiry from {{ $enquiry->name }} will be deleted.">
                        @csrf @method('DELETE')
                        <button class="btn btn-link text-danger p-0"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Delete enquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
