<?php

namespace App\Http\Requests\Admin;

use App\Support\UploadRules;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /** Plain-text settings editable on the Settings page. */
    public const TEXT_KEYS = [
        'tagline', 'footer_about', 'footer_text', 'powered_by', 'meta_description', 'meta_keywords',
        'enquire_button_text', 'home_welcome_eyebrow', 'home_welcome_title', 'home_welcome_text',
        'cta_title', 'cta_text', 'cta_button_text', 'cta_button_url',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tagline' => ['nullable', 'string', 'max:150'],
            'footer_about' => ['nullable', 'string', 'max:400'],
            'footer_text' => ['nullable', 'string', 'max:200'],
            'powered_by' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:300'],
            'enquire_button_text' => ['nullable', 'string', 'max:30'],
            'home_welcome_eyebrow' => ['nullable', 'string', 'max:80'],
            'home_welcome_title' => ['nullable', 'string', 'max:150'],
            'home_welcome_text' => ['nullable', 'string', 'max:2000'],
            'cta_title' => ['nullable', 'string', 'max:120'],
            'cta_text' => ['nullable', 'string', 'max:400'],
            'cta_button_text' => ['nullable', 'string', 'max:40'],
            'cta_button_url' => UploadRules::link(),

            // SVG is deliberately excluded: it can carry script when opened directly.
            'logo' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:png,ico', 'max:512'],
            'og_image' => UploadRules::image(),
            'remove_logo' => ['boolean'],
            'remove_favicon' => ['boolean'],
            'remove_og_image' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'remove_logo' => $this->boolean('remove_logo'),
            'remove_favicon' => $this->boolean('remove_favicon'),
            'remove_og_image' => $this->boolean('remove_og_image'),
        ]);
    }
}
