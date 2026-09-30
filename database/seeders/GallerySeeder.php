<?php

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Database\Seeders\Support\Placeholder;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        if (GalleryAlbum::exists() || GalleryImage::exists()) {
            return;
        }

        $albums = [
            'School Life' => ['Morning assembly', 'Break time with friends'],
            'Academics' => ['Science practical lesson', 'Reading hour in the library'],
            'Sports' => ['Relay race on Sports Day', 'Football tournament final'],
            'Events' => ['Prize-giving day'],
            'Students' => ['Class representatives'],
            'Facilities' => ['Our computer laboratory', 'The school compound'],
            'Co-curricular Activities' => ['Choir rehearsal', 'Drama club performance'],
        ];

        // Varied aspect ratios make the masonry layout visible during development.
        $sizes = [[1200, 900], [900, 1200], [1200, 800], [1000, 1000], [1200, 900], [1200, 750]];
        $seed = 500;
        $order = 1;

        foreach (array_keys($albums) as $i => $name) {
            $album = GalleryAlbum::create(['name' => $name, 'is_active' => true, 'sort_order' => $i + 1]);

            foreach ($albums[$name] as $caption) {
                [$w, $h] = $sizes[$seed % count($sizes)];
                $path = Placeholder::image('gallery', $seed++, $w, $h);

                GalleryImage::create([
                    'gallery_album_id' => $album->id,
                    'image' => $path,
                    'thumbnail' => null,
                    'caption' => $caption,
                    'is_featured' => $order <= 6,
                    'is_active' => true,
                    'sort_order' => $order++,
                ]);
            }
        }
    }
}
