<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Required on every install.
        $this->call([
            AdminUserSeeder::class,
            SettingSeeder::class,
            ContactInformationSeeder::class,
            SocialLinkSeeder::class,
        ]);

        // Sample website content with placeholder images and documents.
        $this->call([
            SliderSeeder::class,
            AboutSectionSeeder::class,
            FeatureSeeder::class,
            NewsSeeder::class,
            GallerySeeder::class,
            FaqSeeder::class,
            ResourceSeeder::class,
        ]);

        if (app()->isLocal()) {
            $this->call(ContactEnquirySeeder::class);
        }
    }
}
