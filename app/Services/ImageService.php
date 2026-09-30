<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores uploaded images on the public disk under a random name, downscaling
 * oversized photos (and optionally generating a thumbnail) with GD so phone
 * camera uploads don't ship multi-megabyte originals to visitors.
 */
class ImageService
{
    private const DISK = 'public';
    private const QUALITY = 82;

    /**
     * @return array{path: string, thumbnail: ?string}
     */
    public function store(UploadedFile $file, string $directory, int $maxWidth = 1920, ?int $thumbWidth = null): array
    {
        $source = $this->load($file);

        // Formats GD can't read (or a missing GD extension) fall back to storing the upload as-is.
        if (! $source) {
            $path = $file->store($directory, self::DISK);

            return ['path' => $path, 'thumbnail' => null];
        }

        $keepPng = $file->getMimeType() === 'image/png' && $this->hasTransparency($source);
        $extension = $keepPng ? 'png' : (function_exists('imagewebp') ? 'webp' : 'jpg');
        $name = Str::random(40);

        $path = trim($directory, '/') . '/' . $name . '.' . $extension;
        $this->write($this->resize($source, $maxWidth), $path, $extension);

        $thumbnail = null;
        if ($thumbWidth) {
            $thumbnail = trim($directory, '/') . '/thumbs/' . $name . '.' . $extension;
            $this->write($this->resize($source, $thumbWidth), $thumbnail, $extension);
        }

        imagedestroy($source);

        return ['path' => $path, 'thumbnail' => $thumbnail];
    }

    /**
     * Store a logo/favicon untouched (keeps SVG/ICO/transparency intact).
     */
    public function storeOriginal(UploadedFile $file, string $directory): string
    {
        return $file->storeAs($directory, Str::random(40) . '.' . $file->guessExtension(), self::DISK);
    }

    public function delete(?string ...$paths): void
    {
        $paths = array_filter($paths, fn ($p) => filled($p) && ! str_starts_with($p, 'http'));
        if ($paths) {
            Storage::disk(self::DISK)->delete($paths);
        }
    }

    private function load(UploadedFile $file): ?\GdImage
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $real = $file->getRealPath();
        $image = match ($file->getMimeType()) {
            'image/jpeg' => @imagecreatefromjpeg($real),
            'image/png' => @imagecreatefrompng($real),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($real) : false,
            'image/gif' => @imagecreatefromgif($real),
            default => false,
        };

        if (! $image) {
            return null;
        }

        if ($file->getMimeType() === 'image/jpeg') {
            $image = $this->applyExifOrientation($image, $real);
        }

        return $image;
    }

    private function resize(\GdImage $source, int $maxWidth): \GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);

        $targetWidth = min($width, $maxWidth);
        $targetHeight = (int) round($height * ($targetWidth / $width));

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        return $canvas;
    }

    private function write(\GdImage $image, string $path, string $extension): void
    {
        ob_start();
        match ($extension) {
            'png' => imagepng($image, null, 8),
            'webp' => imagewebp($image, null, self::QUALITY),
            default => imagejpeg($image, null, self::QUALITY),
        };
        Storage::disk(self::DISK)->put($path, ob_get_clean());
        imagedestroy($image);
    }

    private function hasTransparency(\GdImage $image): bool
    {
        $w = imagesx($image);
        $h = imagesy($image);
        $step = max(1, (int) (min($w, $h) / 40));

        for ($x = 0; $x < $w; $x += $step) {
            for ($y = 0; $y < $h; $y += $step) {
                if (((imagecolorat($image, $x, $y) >> 24) & 0x7F) > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    private function applyExifOrientation(\GdImage $image, string $path): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = @exif_read_data($path)['Orientation'] ?? 1;

        return match ((int) $orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }
}
