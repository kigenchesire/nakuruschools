<?php

namespace Database\Seeders;

use App\Models\Slider;
use Database\Seeders\Support\Placeholder;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        if (Slider::exists()) {
            return;
        }

        $slides = [
            [
                'subtitle' => 'Welcome to Nakuru Schools',
                'title' => 'Inspiring Excellence. Building Futures.',
                'description' => 'A caring learning community where every child is known, challenged and supported to become their very best.',
                'button_text' => 'Enquire Now',
                'button_url' => '/contact',
            ],
            [
                'subtitle' => 'Holistic Education',
                'title' => 'A Place Where Every Learner Can Thrive.',
                'description' => 'Strong academics, rich co-curricular activities and sound values — nurturing confident, disciplined and creative young people.',
                'button_text' => 'Why Choose Us',
                'button_url' => '/about',
            ],
            [
                'subtitle' => 'Admissions Open',
                'title' => 'Join a Community That Believes in Your Child.',
                'description' => 'Discover our programmes, meet our teachers and see our learning environment for yourself. We look forward to welcoming you.',
                'button_text' => 'Download Forms',
                'button_url' => '/resources',
            ],
        ];

        $imageSeeds = [100, 104, 102];

        foreach ($slides as $i => $slide) {
            Slider::create($slide + [
                'image' => Placeholder::image('sliders', $imageSeeds[$i], 1920, 1080),
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
