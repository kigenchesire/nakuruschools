<?php

namespace Database\Seeders;

use App\Models\ContactEnquiry;
use Illuminate\Database\Seeder;

/**
 * Sample enquiries so the inbox and dashboard have data locally.
 * Only seeded when APP_ENV=local.
 */
class ContactEnquirySeeder extends Seeder
{
    public function run(): void
    {
        if (ContactEnquiry::withTrashed()->exists()) {
            return;
        }

        $samples = [
            ['Sample Parent', 'parent@example.com', '0700 000 001', 'Admission enquiry (sample)', 'Hello, I would like to know whether there are vacancies in Grade 4 for next year and when assessments take place. Thank you.', 'unread', 1],
            ['Sample Guardian', 'guardian@example.com', null, 'School transport (sample)', 'Good afternoon. Does the school bus serve the Milimani area, and what are the charges?', 'read', 12],
            ['Sample Visitor', 'visitor@example.com', '0700 000 003', 'School visit (sample)', 'We are relocating to Nakuru and would like to visit the school next week. Which days are convenient?', 'responded', 40],
        ];

        foreach ($samples as [$name, $email, $phone, $subject, $message, $status, $daysAgo]) {
            $enquiry = ContactEnquiry::create(compact('name', 'email', 'phone', 'subject', 'message', 'status') + [
                'ip_address' => '127.0.0.1',
                'read_at' => $status === 'unread' ? null : now()->subDays($daysAgo - 1),
            ]);
            $enquiry->forceFill(['created_at' => now()->subDays($daysAgo), 'updated_at' => now()->subDays($daysAgo)])->saveQuietly();
        }
    }
}
