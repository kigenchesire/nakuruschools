<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        if (Faq::exists()) {
            return;
        }

        $faqs = [
            ['How do I apply for admission?', "Download the admission form from our Resources page or collect one from the school office. Submit the completed form with a copy of the learner's birth certificate and latest report. Our admissions team will then contact you to arrange an assessment and interview."],
            ['Which curriculum does the school follow?', 'We follow the Competency-Based Curriculum (CBC), with a strong focus on literacy, numeracy, science, creative arts and values education.'],
            ['What are the school fees?', 'The current fee structure is available on the Resources page. For payment plans or any fee-related questions, please contact the accounts office.'],
            ['Do you offer school transport?', 'Yes. School transport serves several routes within Nakuru town and its environs. Contact the office for routes, pick-up times and charges.'],
            ['What co-curricular activities are available?', 'Learners can take part in football, athletics, netball, swimming, music, drama, scouting, environmental club, coding club and more.'],
            ['What are the school hours?', "Classes run from 7:30 am to 4:00 pm, Monday to Friday. The school office is open from 8:00 am to 5:00 pm."],
            ['Can I visit the school before applying?', 'Absolutely. We welcome visits by appointment — contact us to arrange a tour and meet our teachers.'],
            ['How does the school communicate with parents?', 'We share updates through termly reports, parents’ meetings, SMS notifications, email and the News section of this website.'],
        ];

        foreach ($faqs as $i => [$question, $answer]) {
            Faq::create(['question' => $question, 'answer' => $answer, 'is_active' => true, 'sort_order' => $i + 1]);
        }
    }
}
