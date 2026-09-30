<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

/**
 * Every supported platform gets a row. A few are enabled with generic URLs so
 * the icons are visible during development; set the real profile URLs under
 * Admin → Social Media.
 */
class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        $placeholders = [
            'facebook' => 'https://www.facebook.com/',
            'instagram' => 'https://www.instagram.com/',
            'youtube' => 'https://www.youtube.com/',
            'whatsapp' => 'https://wa.me/254700000000',
        ];

        foreach (array_keys(SocialLink::PLATFORMS) as $i => $platform) {
            SocialLink::firstOrCreate(['platform' => $platform], [
                'url' => $placeholders[$platform] ?? null,
                'is_active' => isset($placeholders[$platform]),
                'sort_order' => $i + 1,
            ]);
        }
    }
}
