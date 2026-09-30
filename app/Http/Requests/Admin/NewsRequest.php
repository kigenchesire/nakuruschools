<?php

namespace App\Http\Requests\Admin;

use App\Models\News;
use App\Support\UploadRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $news = $this->route('news');

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash', Rule::unique('news', 'slug')->ignore($news?->id)],
            'category' => ['nullable', 'string', 'max:60'],
            'image' => UploadRules::image(),
            'remove_image' => ['boolean'],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'content' => ['required', 'string', 'max:200000'],
            'status' => ['required', Rule::in([News::STATUS_DRAFT, News::STATUS_PUBLISHED])],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'remove_image' => $this->boolean('remove_image'),
        ]);
    }
}
