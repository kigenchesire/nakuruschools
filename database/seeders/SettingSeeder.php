<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\SiteData;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteData::DEFAULT_SETTINGS as $key => $value) {
            // Never overwrite values an administrator has already changed.
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
