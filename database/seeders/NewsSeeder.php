<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Database\Seeders\Support\Placeholder;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        if (News::withTrashed()->exists()) {
            return;
        }

        $authorId = User::query()->value('id');

        $articles = [
            [
                'title' => 'Admissions Open for the 2027 Academic Year',
                'category' => 'Announcements',
                'excerpt' => 'We are now accepting applications for all grades for the 2027 academic year. Download the admission form or visit our office to begin.',
                'content' => '<p>We are pleased to announce that admissions for the <strong>2027 academic year</strong> are now open for all grades.</p><p>Parents and guardians are invited to visit the school, meet our teachers and tour our facilities. Admission forms can be downloaded from the <a href="/resources">Resources</a> page or collected from the school office.</p><h2>How to apply</h2><ol><li>Download and complete the admission form.</li><li>Attach copies of the learner’s birth certificate and latest report.</li><li>Submit the form at the school office or by email.</li><li>Our admissions team will contact you to arrange an assessment and interview.</li></ol><p>For any questions, please <a href="/contact">contact us</a> — we would be delighted to help.</p>',
                'is_featured' => true,
                'days_ago' => 2,
            ],
            [
                'title' => 'Learners Shine at the County Science and Engineering Fair',
                'category' => 'Academics',
                'excerpt' => 'Our young scientists impressed judges with innovative projects on clean water, renewable energy and smart farming.',
                'content' => '<p>Our learners represented the school with distinction at this year’s county Science and Engineering Fair, presenting projects on clean water, renewable energy and smart farming.</p><blockquote><p>“The creativity and confidence our learners showed was inspiring,” said the head of science.</p></blockquote><p>Congratulations to all participants and their teachers for months of hard work and curiosity.</p>',
                'days_ago' => 9,
            ],
            [
                'title' => 'Annual Sports Day Brings the School Community Together',
                'category' => 'Sports',
                'excerpt' => 'Races, relays, football and plenty of team spirit — a memorable day of sport for learners, staff and parents.',
                'content' => '<p>Our annual Sports Day was a colourful celebration of talent, teamwork and school spirit. Learners competed in track events, relays, football and netball, cheered on by parents and staff.</p><p>Thank you to all the families who joined us, and well done to every learner who took part.</p>',
                'days_ago' => 16,
            ],
            [
                'title' => 'Music and Drama Festival: Our Performers Advance to Regionals',
                'category' => 'Co-curricular',
                'excerpt' => 'Our choir and drama club delivered outstanding performances and qualified for the regional festival.',
                'content' => '<p>Our choir, traditional dance troupe and drama club delivered outstanding performances at the sub-county Music and Drama Festival and have qualified for the regional level.</p><p>We are proud of their dedication and wish them the very best in the next round.</p>',
                'days_ago' => 24,
            ],
            [
                'title' => 'New Library and Digital Learning Corner Opened',
                'category' => 'Community',
                'excerpt' => 'A refurbished library with new books and a digital learning corner is now open to all learners.',
                'content' => '<p>We are excited to open our refurbished library, featuring hundreds of new books and a digital learning corner with tablets for research and reading programmes.</p><p>Reading builds imagination and lifelong learning — we encourage every learner to make full use of this wonderful space.</p>',
                'days_ago' => 35,
            ],
            [
                'title' => 'Term Two Parents’ Meeting and Academic Clinic',
                'category' => 'Events',
                'excerpt' => 'Parents are invited to meet class teachers, review progress and discuss learners’ goals for the rest of the year.',
                'content' => '<p>All parents and guardians are invited to the Term Two Parents’ Meeting and Academic Clinic. This is an opportunity to meet class teachers, review learners’ progress and agree on goals for the rest of the year.</p><p>Please check the school calendar on the Resources page for the date and time.</p>',
                'days_ago' => 48,
            ],
            [
                'title' => 'Environmental Club Plants 500 Trees Around the Campus',
                'category' => 'Community',
                'excerpt' => 'Learners and staff joined hands to green our school and the surrounding community.',
                'content' => '<p>Our Environmental Club led a tree-planting day, planting 500 indigenous trees around the campus and neighbourhood. Learners will care for the seedlings as part of their community service.</p>',
                'days_ago' => 60,
            ],
        ];

        foreach ($articles as $i => $article) {
            $published = now()->subDays($article['days_ago'])->setTime(9, 0);
            unset($article['days_ago']);

            News::create($article + [
                'image' => Placeholder::image('news', 300 + $i, 1600, 1000),
                'status' => News::STATUS_PUBLISHED,
                'published_at' => $published,
                'author_id' => $authorId,
            ]);
        }

        News::create([
            'title' => 'Draft: End of Year Prize-Giving Day',
            'category' => 'Events',
            'content' => '<p>Details of this year’s prize-giving ceremony will be shared here. (This is a sample draft, so it is not visible on the website.)</p>',
            'status' => News::STATUS_DRAFT,
            'published_at' => null,
            'author_id' => $authorId,
        ]);
    }
}
