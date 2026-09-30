@extends('layouts.app')

@section('title', 'Frequently Asked Questions')
@section('meta_description', 'Answers to common questions about admissions, fees, transport, curriculum and school life at ' . $site->schoolName() . '.')

@push('meta')
    @if ($faqs->isNotEmpty())
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
            ])->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @endif
@endpush

@section('content')
    <x-frontend.page-header title="Frequently Asked Questions" subtitle="Everything parents and guardians commonly ask us, answered." />

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($faqs->isNotEmpty())
                        <x-frontend.faq-accordion :faqs="$faqs" heading-tag="h2" />
                    @else
                        <div class="empty-state">
                            <i class="bi bi-question-circle" aria-hidden="true"></i>
                            <h2 class="h5">No questions yet</h2>
                            <p class="mb-0">Frequently asked questions will be published here soon.</p>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="help-card brand-pattern position-sticky" style="top: calc(var(--header-h) + 1.5rem)">
                        <span class="icon-badge mb-3" aria-hidden="true" style="background: var(--school-yellow)"><i class="bi bi-chat-heart-fill"></i></span>
                        <h2 class="h3">Have questions?</h2>
                        <p>Our team is ready to help you learn more about {{ $site->schoolName() }}.</p>
                        <a href="{{ route('contact') }}#contact-form" class="btn btn-gold w-100 mb-2">Contact Us</a>
                        @if ($site->contact()->phone)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site->contact()->phone) }}" class="btn btn-outline-light-soft w-100"><i class="bi bi-telephone-fill me-1" aria-hidden="true"></i> {{ $site->contact()->phone }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
