@props([
    'title' => null,
    'text' => null,
    'buttonText' => null,
    'buttonUrl' => null,
    'secondaryText' => null,
    'secondaryUrl' => null,
])
<section {{ $attributes->merge(['class' => 'section-sm']) }}>
    <div class="container">
        <div class="cta-band brand-pattern reveal">
            <span class="cta-ring cta-ring-1" aria-hidden="true"></span>
            <span class="cta-ring cta-ring-2" aria-hidden="true"></span>
            <div class="row align-items-center g-4 position-relative">
                <div class="col-lg-7">
                    <span class="eyebrow on-dark">Admissions</span>
                    <h2>{{ $title ?? $site->setting('cta_title') }}</h2>
                    <p>{{ $text ?? $site->setting('cta_text') }}</p>
                </div>
                <div class="col-lg-5 d-flex flex-column flex-sm-row flex-wrap gap-3 justify-content-lg-end">
                    <a href="{{ $buttonUrl ?? url($site->setting('cta_button_url') ?: '/contact') }}" class="btn btn-gold btn-lg btn-icon-end text-nowrap">
                        {{ $buttonText ?? $site->setting('cta_button_text') }} <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                    </a>
                    @if ($secondaryText)
                        <a href="{{ $secondaryUrl }}" class="btn btn-outline-light-soft btn-lg text-nowrap">{{ $secondaryText }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
