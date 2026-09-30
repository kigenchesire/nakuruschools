<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Database\Seeders\Support\Placeholder;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'introduction',
                'title' => 'Nurturing confident, capable and caring learners',
                'subtitle' => 'Get to know our story, our purpose and the values that guide everything we do.',
                'summary' => 'At Nakuru Schools, we are committed to nurturing learners who are confident, disciplined, creative and prepared to make a positive contribution to society.',
                'content' => '<p>At Nakuru Schools, we are committed to nurturing learners who are confident, disciplined, creative and prepared to make a positive contribution to society.</p>'
                    . '<p>Our dedicated teachers work closely with parents to understand each learner, build strong foundations in literacy and numeracy, and open doors to science, technology, the arts and sport. We believe that education is about more than examinations — it is about character, curiosity and community.</p>'
                    . '<p>From our well-resourced classrooms to our playing fields, every part of school life is designed to help children grow in knowledge, skills and values.</p>',
                'icon' => 'bi-building',
                'image' => Placeholder::image('about', 210, 1400, 1100),
            ],
            [
                'key' => 'mission',
                'title' => 'Our Mission',
                'summary' => 'To provide holistic, quality education that develops every learner’s potential in a safe, caring and inspiring environment.',
                'icon' => 'bi-bullseye',
            ],
            [
                'key' => 'vision',
                'title' => 'Our Vision',
                'summary' => 'To be a centre of excellence producing responsible, innovative and God-fearing citizens ready to serve Kenya and the world.',
                'icon' => 'bi-eye-fill',
            ],
            [
                'key' => 'core_values',
                'title' => 'Core Values',
                'summary' => "Integrity\nExcellence\nRespect\nDiscipline\nTeamwork\nCompassion",
                'icon' => 'bi-gem',
            ],
            [
                'key' => 'history',
                'title' => 'Our History',
                'summary' => 'The story of how our school community began and grew.',
                'content' => '<p>This section tells the story of Nakuru Schools — when and why the school was founded, the people who shaped it and the milestones along the way.</p>'
                    . '<p><strong>Replace this placeholder text</strong> from <em>Admin → About Us → Our History</em> with the school’s real history, key dates and achievements.</p>',
                'icon' => 'bi-hourglass-split',
            ],
            [
                'key' => null,
                'title' => 'Our Learning Approach',
                'subtitle' => 'Competency-Based Curriculum',
                'summary' => 'How we teach and how learners grow with us.',
                'content' => '<p>We implement the Competency-Based Curriculum (CBC) with a focus on practical, learner-centred teaching. Lessons connect knowledge to real life, encourage questions and build skills such as communication, collaboration, critical thinking and digital literacy.</p>'
                    . '<ul><li>Small class sizes and individual attention</li><li>Regular assessment and feedback to parents</li><li>Clubs, sports and creative arts for every learner</li><li>Guidance, counselling and pastoral care</li></ul>',
                'icon' => 'bi-lightbulb',
            ],
        ];

        foreach ($sections as $i => $section) {
            $match = $section['key'] ? ['key' => $section['key']] : ['key' => null, 'title' => $section['title']];

            AboutSection::firstOrCreate($match, $section + ['is_active' => true, 'sort_order' => $i + 1]);
        }
    }
}
