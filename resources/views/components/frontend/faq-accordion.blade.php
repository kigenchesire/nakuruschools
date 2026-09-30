@props(['faqs', 'id' => 'faqAccordion', 'headingTag' => 'h3'])
<div class="accordion faq-accordion" id="{{ $id }}">
    @foreach ($faqs as $faq)
        <div class="accordion-item">
            <{{ $headingTag }} class="accordion-header">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
                        data-bs-target="#{{ $id }}-{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                        aria-controls="{{ $id }}-{{ $faq->id }}">
                    {{ $faq->question }}
                </button>
            </{{ $headingTag }}>
            <div id="{{ $id }}-{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#{{ $id }}">
                <div class="accordion-body">{{ $faq->answer }}</div>
            </div>
        </div>
    @endforeach
</div>
