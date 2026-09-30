<?php

namespace App\Http\Requests\Admin;

use App\Support\UploadRules;
use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:60'],
            'file' => UploadRules::document($this->isMethod('post')),
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Allowed file types: PDF, Word, Excel, PowerPoint, JPG, PNG and ZIP.',
            'file.max' => 'Files may not be larger than ' . (UploadRules::DOCUMENT_MAX_KB / 1024) . ' MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
}
