<?php

namespace App\Support;

use App\Models\Resource;

/**
 * Upload validation shared by the admin forms, so limits stay consistent.
 */
class UploadRules
{
    public const IMAGE_MAX_KB = 5120;
    public const DOCUMENT_MAX_KB = 20480;

    /** Raster photos: stored re-encoded by ImageService. */
    public static function image(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'file',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:' . self::IMAGE_MAX_KB,
            'dimensions:min_width=200,min_height=150,max_width=10000,max_height=10000',
        ];
    }

    public static function document(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'file',
            'mimes:' . implode(',', Resource::ALLOWED_EXTENSIONS),
            'max:' . self::DOCUMENT_MAX_KB,
        ];
    }

    /** Relative paths (/contact), anchors, absolute http(s), mailto: and tel: links. */
    public static function link(): array
    {
        return ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/|#|mailto:|tel:)/i'];
    }
}
