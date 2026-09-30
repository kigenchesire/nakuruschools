<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\User;
use Database\Seeders\Support\Placeholder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        if (Resource::withTrashed()->exists()) {
            return;
        }

        $userId = User::query()->value('id');

        $resources = [
            ['Admission Form', 'Admissions', 'Application form for new learners joining any grade.', ['Learner details', 'Parent / guardian details', 'Previous school', 'Medical information', 'Declaration and signature']],
            ['Fee Structure 2027', 'Fee Structures', 'Tuition and other charges for the 2027 academic year, with payment details.', ['Tuition per term', 'Transport (optional)', 'Lunch programme (optional)', 'Payment methods and deadlines']],
            ['School Calendar 2027', 'Calendars', 'Term dates, half-term breaks, exams and key school events.', ['Term 1 dates', 'Term 2 dates', 'Term 3 dates', 'Parents meetings and events']],
            ['School Prospectus', 'Brochures', 'An introduction to our school, programmes, facilities and values.', ['About the school', 'Our curriculum', 'Co-curricular programme', 'Facilities', 'How to join us']],
            ['Parents Handbook & School Policies', 'Policies', 'Rules, code of conduct, uniform, safeguarding and communication policies.', ['Code of conduct', 'Uniform policy', 'Safeguarding', 'Attendance', 'Communication with parents']],
        ];

        foreach ($resources as [$title, $category, $description, $lines]) {
            [$path, $size] = Placeholder::pdf('resources/' . Str::random(40) . '.pdf', $title, $lines);

            Resource::create([
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'file' => $path,
                'original_name' => Str::slug($title) . '.pdf',
                'file_type' => 'pdf',
                'file_size' => $size,
                'is_active' => true,
                'created_by' => $userId,
            ]);
        }
    }
}
