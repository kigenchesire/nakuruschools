<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        if (Feature::exists()) {
            return;
        }

        $why = [
            ['Quality Education', 'A strong, well-structured curriculum delivered with care and high expectations for every learner.', 'bi-mortarboard-fill'],
            ['Experienced Teachers', 'Qualified, dedicated teachers who know each child and are committed to their growth.', 'bi-person-workspace'],
            ['Holistic Development', 'We nurture the mind, body and character — not just examination results.', 'bi-heart-pulse-fill'],
            ['Safe & Supportive', 'A secure, caring environment where learners feel valued, respected and happy.', 'bi-shield-check'],
            ['Character Development', 'Values of integrity, discipline and responsibility woven into daily school life.', 'bi-award-fill'],
            ['Co-curricular Activities', 'Sports, music, drama, clubs and competitions that build talent and teamwork.', 'bi-trophy-fill'],
            ['Modern Learning Environment', 'Well-equipped classrooms, library and digital learning resources.', 'bi-laptop'],
            ['Strong Academic Foundation', 'Solid literacy, numeracy and problem-solving skills for lifelong learning.', 'bi-journal-bookmark-fill'],
        ];

        foreach ($why as $i => [$title, $description, $icon]) {
            Feature::create(['group' => 'why_choose_us', 'title' => $title, 'description' => $description, 'icon' => $icon, 'sort_order' => $i + 1]);
        }

        // Placeholder figures: update with the school's real numbers.
        $highlights = [
            ['Years of Excellence', '15+', 'bi-calendar-check'],
            ['Happy Learners', '1,200+', 'bi-people-fill'],
            ['Qualified Teachers', '60+', 'bi-person-badge'],
            ['Clubs & Activities', '20+', 'bi-trophy'],
        ];

        foreach ($highlights as $i => [$title, $value, $icon]) {
            Feature::create(['group' => 'highlight', 'title' => $title, 'value' => $value, 'icon' => $icon, 'sort_order' => 20 + $i]);
        }
    }
}
