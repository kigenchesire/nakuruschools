<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates the first administrator. Credentials come from ADMIN_EMAIL /
 * ADMIN_PASSWORD in .env; the fallbacks below are for local development
 * only and must be changed after the first sign-in.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');

        if (User::withTrashed()->where('email', $email)->exists()) {
            $this->command?->info("Admin user {$email} already exists; left unchanged.");

            return;
        }

        User::create([
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => $email,
            'phone' => null,
            'status' => User::STATUS_ACTIVE,
            'password' => env('ADMIN_PASSWORD', 'ChangeMe@2026'),
        ])->forceFill(['email_verified_at' => now()])->save();

        $this->command?->warn("Admin user created: {$email}. Change the password after your first sign-in.");
    }
}
