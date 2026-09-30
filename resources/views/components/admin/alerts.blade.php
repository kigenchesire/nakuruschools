{{-- Flash messages render as toasts (shown by admin.js); validation errors as a summary alert. --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    @foreach (['success' => ['success', 'bi-check-circle-fill'], 'error' => ['danger', 'bi-exclamation-triangle-fill'], 'warning' => ['warning', 'bi-exclamation-circle-fill']] as $key => [$variant, $icon])
        @if (session($key))
            <div class="toast align-items-center text-bg-{{ $variant }}" role="status" aria-live="polite" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body"><i class="bi {{ $icon }} me-2" aria-hidden="true"></i>{{ session($key) }}</div>
                    <button type="button" class="btn-close {{ $variant === 'warning' ? '' : 'btn-close-white' }} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    @endforeach
</div>

@if ($errors->any() && ! $errors->hasBag('password'))
    <div class="alert alert-danger d-flex gap-3 align-items-start shadow-sm" role="alert">
        <i class="bi bi-exclamation-octagon-fill fs-5" aria-hidden="true"></i>
        <div>
            <strong>Please fix the following {{ \Illuminate\Support\Str::plural('error', $errors->count()) }}:</strong>
            <ul class="mb-0 mt-1 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
