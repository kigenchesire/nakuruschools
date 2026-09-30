<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $phone = ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\s-]{7,30}$/'];

        return [
            'school_name' => ['required', 'string', 'max:150'],
            'phone' => $phone,
            'alt_phone' => $phone,
            'email' => ['nullable', 'email', 'max:150'],
            'alt_email' => ['nullable', 'email', 'max:150'],
            'physical_address' => ['nullable', 'string', 'max:255'],
            'postal_address' => ['nullable', 'string', 'max:255'],
            'office_hours' => ['nullable', 'string', 'max:255'],
            'google_maps_url' => ['nullable', 'url:https', 'max:2000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number (digits, spaces, +, - and brackets only).',
            'alt_phone.regex' => 'Enter a valid phone number (digits, spaces, +, - and brackets only).',
        ];
    }

    protected function prepareForValidation(): void
    {
        // People often paste the whole <iframe> from Google Maps; keep just its src.
        $maps = (string) $this->input('google_maps_url');
        if (preg_match('/src="([^"]+)"/i', $maps, $m)) {
            $this->merge(['google_maps_url' => html_entity_decode($m[1])]);
        }
    }
}
