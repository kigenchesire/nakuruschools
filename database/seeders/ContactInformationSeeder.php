<?php

namespace Database\Seeders;

use App\Models\ContactInformation;
use Illuminate\Database\Seeder;

/**
 * Placeholder contact details (Nakuru town centre coordinates).
 * Replace them under Admin → Contact Information.
 */
class ContactInformationSeeder extends Seeder
{
    public function run(): void
    {
        if (ContactInformation::exists()) {
            return;
        }

        ContactInformation::create([
            'school_name' => 'Nakuru Schools',
            'phone' => '+254 700 000 000',
            'alt_phone' => '+254 711 000 000',
            'email' => 'info@nakuruschools.co.ke',
            'alt_email' => 'admissions@nakuruschools.co.ke',
            'physical_address' => 'Nakuru, Nakuru County, Kenya',
            'postal_address' => 'P.O. Box 00000 – 20100, Nakuru',
            'office_hours' => 'Mon – Fri: 8:00 am – 5:00 pm',
            'google_maps_url' => null,
            'latitude' => -0.3030990,
            'longitude' => 36.0800260,
        ]);
    }
}
