<?php

namespace App\Http\Requests\Admin;

use App\Support\UploadRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Store accepts several images at once (`images[]`) sharing one album;
 * update edits a single image and may replace its file.
 */
class GalleryImageRequest extends FormRequest
{
    public const MAX_FILES = 20;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $shared = [
            'gallery_album_id' => ['nullable', 'integer', 'exists:gallery_albums,id'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];

        if ($this->isMethod('post')) {
            return $shared + [
                'images' => ['required', 'array', 'min:1', 'max:' . self::MAX_FILES],
                'images.*' => UploadRules::image(true),
                'caption' => ['nullable', 'string', 'max:200'],
            ];
        }

        return $shared + [
            'image' => UploadRules::image(),
            'caption' => ['nullable', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function attributes(): array
    {
        return ['images.*' => 'image', 'gallery_album_id' => 'album'];
    }

    public function messages(): array
    {
        return ['images.max' => 'You can upload up to ' . self::MAX_FILES . ' images at a time.'];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
