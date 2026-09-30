<?php

namespace App\Http\Requests\Admin;

use App\Models\Feature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group' => ['required', Rule::in(array_keys(Feature::GROUPS))],
            'title' => ['required', 'string', 'max:100'],
            'value' => ['nullable', 'string', 'max:30', 'required_if:group,highlight'],
            'description' => ['nullable', 'string', 'max:400'],
            'icon' => ['nullable', 'string', 'max:60', 'regex:/^bi-[a-z0-9-]+$/'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function messages(): array
    {
        return [
            'icon.regex' => 'Use a Bootstrap Icons class name such as bi-mortarboard.',
            'value.required_if' => 'Highlights need a figure, e.g. "1,200+".',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
}
