<?php

use App\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $babaraIdemTestimonial = factory(Testimonial::class)->create([
            'name' => 'Barbara Idem',
            'text' => 'The courses were fascinating. My trainer is excellent, his knowledge, experience and ability to translate technical jargon in to business language made it easy to understand',
        ]);

        $oreoluwaTestimonial = factory(Testimonial::class)->create([
            'name' => 'Oreoluwa',
            'text' => 'I was coming from a very low knowledge base and I am not a techie, so it was pitched perfectly to increase my awareness',
        ]);

        $seunOdebunmiTestimonial = factory(Testimonial::class)->create([
            'name' => 'Seun Odebunmi',
            'text' => 'Knowledgeable, Professional, Proactive and Available',
        ]);

        $oduduTestimonial = factory(Testimonial::class)->create([
            'name' => 'Odudu',
            'text' => 'The courses were well structured with both interactive activities and theories. The trainer had a depth of knowledge and shared a lot of best practices with the group',
        ]);

        $ekeneTestimonial = factory(Testimonial::class)->create([
            'name' => 'Ekene',
            'text' => 'I believe that a student’s learning experience is reflective of the teacher’s aptitude and knowledge on the subject, and the skill to convey the information. At Treten Academy, they are the best',
        ]);

        $faridTestimonial = factory(Testimonial::class)->create([
            'name' => 'Farid Argungu',
            'text' => 'Unlike the typical educational system within the country, I got used to practicing everything theory that was taught. The CISCO lab environment is phenomenal; I must commend',
        ]);

        $anoynmousTestimonial = factory(Testimonial::class)->create([
            'name' => 'Anoynmous',
            'text' => 'Treten Academy has shown me that ICT security is no longer a luxury, but a necessity',
        ]);
        
        $anoynmousTestimonial = factory(Testimonial::class)->create([
            'name' => 'Anoynmous',
            'text' => 'Treten Academy has shown me that ICT security is no longer a luxury, but a necessity',
        ]);
    }
}
