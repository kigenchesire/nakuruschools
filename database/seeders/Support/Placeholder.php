<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Development placeholders: stylised landscape illustrations (SVG) and simple
 * PDF documents, so every section renders before real photos and files exist.
 * They are obviously illustrations, not photos, and are replaced via the CMS.
 */
class Placeholder
{
    /** [sky top, sky bottom, sun, [far hill, mid hill, near hill]] */
    private const PALETTES = [
        ['#0b2447', '#3b5b8c', '#f2b705', ['#1d3a66', '#132c52', '#071a33']],
        ['#f7c873', '#fbe8c8', '#c8102e', ['#6b8f71', '#4c7358', '#2f5240']],
        ['#8ec5e8', '#e8f4fb', '#ffd166', ['#7fb77e', '#5a9a5f', '#3b7d45']],
        ['#c8102e', '#f29e4c', '#fde2a7', ['#7a2e3a', '#5a1f2c', '#3a121d']],
        ['#1f6fb2', '#9ecbe8', '#fff3b0', ['#2d6a4f', '#1b4332', '#0f2d1f']],
        ['#f2b705', '#fff1c1', '#ffffff', ['#1d3a66', '#16325e', '#0b2447']],
    ];

    /** Writes an SVG illustration to the public disk and returns its path. */
    public static function image(string $directory, int $seed, int $width = 1600, int $height = 1000, bool $lake = true): string
    {
        mt_srand($seed * 7919);
        [$skyTop, $skyBottom, $sun, $hills] = self::PALETTES[$seed % count(self::PALETTES)];

        $sunX = mt_rand((int) ($width * .15), (int) ($width * .85));
        $sunY = mt_rand((int) ($height * .18), (int) ($height * .38));
        $sunR = (int) ($height * mt_rand(7, 11) / 100);
        $glowR = (int) ($sunR * 2.8);

        $layers = '';
        foreach ($hills as $i => $color) {
            $base = $height * (0.52 + $i * 0.12);
            $layers .= '<path d="' . self::ridge($width, $height, $base, $height * (0.16 - $i * 0.03)) . '" fill="' . $color . '"/>';

            // Lake Nakuru-style water band, with a few flamingos, between the far and mid hills.
            if ($lake && $i === 0) {
                $lakeY = (int) ($base + $height * .06);
                $layers .= '<rect x="0" y="' . $lakeY . '" width="' . $width . '" height="' . (int) ($height * .1) . '" fill="#cfe8f5" opacity=".75"/>';
                for ($f = 0; $f < 9; $f++) {
                    $fx = mt_rand(20, $width - 20);
                    $fy = $lakeY + mt_rand(6, (int) ($height * .07));
                    $layers .= '<ellipse cx="' . $fx . '" cy="' . $fy . '" rx="' . (int) ($width * .006) . '" ry="' . (int) ($width * .004) . '" fill="#f48fb1"/>';
                }
            }
        }

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}" preserveAspectRatio="xMidYMid slice">
<defs><linearGradient id="sky" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="{$skyTop}"/><stop offset="1" stop-color="{$skyBottom}"/></linearGradient>
<radialGradient id="glow"><stop offset="0" stop-color="{$sun}" stop-opacity=".55"/><stop offset="1" stop-color="{$sun}" stop-opacity="0"/></radialGradient></defs>
<rect width="{$width}" height="{$height}" fill="url(#sky)"/>
<circle cx="{$sunX}" cy="{$sunY}" r="{$glowR}" fill="url(#glow)"/>
<circle cx="{$sunX}" cy="{$sunY}" r="{$sunR}" fill="{$sun}" opacity=".9"/>
{$layers}
</svg>
SVG;

        $path = trim($directory, '/') . '/placeholder-' . $seed . '.svg';
        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    /** Smooth hill silhouette across the full width. */
    private static function ridge(int $width, int $height, float $base, float $amplitude): string
    {
        $points = 6;
        $step = $width / $points;
        $y = fn () => round($base - mt_rand(0, (int) $amplitude), 1);

        $d = 'M0 ' . $y() . ' Q' . round($step / 2) . ' ' . $y() . ' ' . round($step) . ' ' . $y();
        for ($i = 2; $i <= $points; $i++) {
            $d .= ' T' . round($step * $i) . ' ' . $y();
        }

        return $d . " L{$width} {$height} L0 {$height} Z";
    }

    /** Writes a one-page PDF to the private disk and returns [path, size]. */
    public static function pdf(string $path, string $title, array $lines): array
    {
        $escape = fn (string $text) => str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);

        $stream = "0.043 0.141 0.278 rg 0 762 595 80 re f\n"
            . "0.949 0.718 0.020 rg 0 756 595 6 re f\n"
            . "BT /F1 22 Tf 1 1 1 rg 50 792 Td (" . $escape($title) . ") Tj ET\n"
            . "BT /F2 10 Tf 1 1 1 rg 50 774 Td (Nakuru Schools - sample document for development) Tj ET\n";

        $y = 710;
        foreach ($lines as $line) {
            $stream .= "BT /F2 12 Tf 0.09 0.13 0.18 rg 50 {$y} Td (" . $escape($line) . ") Tj ET\n";
            $y -= 22;
        }
        $stream .= "BT /F2 9 Tf 0.36 0.4 0.47 rg 50 60 Td (Placeholder file - replace it from Admin > Resources.) Tj ET\n";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> >>',
            "<< /Length " . strlen($stream) . " >>\nstream\n{$stream}endstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objects as $i => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n{$object}\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

        Storage::disk('local')->put($path, $pdf);

        return [$path, strlen($pdf)];
    }
}
