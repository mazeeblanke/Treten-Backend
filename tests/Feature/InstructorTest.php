<?php

namespace Tests\Feature;

use App\BlogPost;
use App\Instructor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InstructorTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;

    public function testViewInstructorPageByPage()
    {
        // Instructor::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        $ola = factory(Instructor::class)->create();
        $ola->details->status = 'active';
        $ola->details->first_name = 'ola';
        $ola->details->save();


        $page = 2;
        $pageSize= 1;

        $response = $this
            ->json(
                'GET',
                "/api/instructors?page=$page&pageSize=$pageSize"
            );

        $response->assertJsonFragment([
            'currentPage' => $page,
        ]);

        $response->assertJsonCount(1, 'data');
        $response->assertSee($ola->first_name);
        // $response->assertSee('Successfully fetched instructors');

        // $response->assertStatus(200);
        $response->assertSuccessful();
    }

    public function testViewAnInstructor () {
        // Instructor::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        $peter = factory(Instructor::class)->create();
        $peter->details->status = 'active';
        $peter->details->first_name = 'peter';
        $peter->details->save();

        $response = $this
            ->json(
                'GET',
                "/api/instructor/{$john->instructor_slug}"
            );

        // $response->assertJsonFragment([
        //     "message" => "Successfully fetched instructor"
        // ]);

        $response->assertSee($john->details->first_name);
        $response->assertDontSee($peter->details->first_name);

        $response->assertStatus(200);
    }

    public function testUpdateAnInstructor () {
        // Instructor::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        $peter = factory(Instructor::class)->create();
        $peter->details->status = 'active';
        $peter->details->first_name = 'peter';
        $peter->details->save();

        $payload = [
            "bio" => "john's bio",
            "title" => "Network Engineer",
            "certifications" => [
                "year" => "2019",
                "name" => "CCNA"
            ],
            "socialLinks" => [
                "facebook" => '',
                "twitter" => null,
                "linkedin" => null,
            ],
            "workExperience" => [
                "job_description" => "Responsible for cordinating the team",
                "job_title" => "Softwarw engineer",
                "name_of_company" => "Cardinalstone",
                "end_date" => 2019,
                "start_date" => 2012,
            ],
            "education" => [
                "name_of_institution" => "Covenant university",
                "qualification_obtained" => "BSC",
                "end_date" => 2019,
                "start_date" => 2012,
            ],
        ];

        $response = $this
            ->json(
                'POST',
                "/api/instructor/{$john->id}",
                $payload
            );

        $response->assertJsonFragment([
            "message" => "Successfully updated instructor"
        ]);

        $response->assertSee($john->details->first_name);
        $response->assertDontSee($peter->details->first_name);

        $response->assertJsonFragment([
            'bio' => $payload['bio'],
            'title' => $payload['title'],
            'certifications' => $payload['certifications'],
            'social_links' => [
                "facebook" => '',
                "twitter" => '',
                "linkedin" => '',
            ],
            'work_experience' => $payload['workExperience'],
            'education' => $payload['education']
        ]);

        $response->assertStatus(200);
    }

    public function testCannotUpdateAnInstructorWithNonExistingWrongId () {
        // Instructor::unsetEventDispatcher();

        $payload = [
        ];

        $response = $this
            ->json(
                'POST',
                "/api/instructor/{78}",
                $payload
            );

        $response->assertSee("No query results for model");

        $response->assertStatus(404);
    }
}
