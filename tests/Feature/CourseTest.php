<?php

namespace Tests\Feature;

use App\Course;
use App\CourseCategory;
use App\CoursePath;
use App\Instructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanCreateaCourse()
    {
        $faqs = [
            [
                'question' => 'First question',
                'answer' => 'The right answer',
            ],
            [
                'question' => 'second question',
                'answer' => 'The second right answer',
            ],
        ];

        $modules = [
            [
                'title' => 'first title',
                'description' => 'first description',
            ],
            [
                'title' => 'second title',
                'description' => 'second description',
            ],
        ];

        // TODO: change to admin later
        Instructor::unsetEventDispatcher();
        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        $associate = factory(CourseCategory::class)->create(['name' => 'associate']);

        $ccna = factory(CoursePath::class)->create(['name' => 'ccna']);

        $firstCourse = factory(Course::class)->create([
            'course_path_id' => $ccna->id,
            'author_id' => $john->details->id,
        ]);

        $payload = [
            'title' => 'A course title',
            'description' => 'A course description',
            'price' => 199.99,
            'isPublished' => 1,
            'bannerImage' => UploadedFile::fake()->image('test.png'),
            'category' => 'new category',
            'duration' => 10,
            'coursePath' => $ccna->id,
            'coursePathPosition' => 1,
            'modules' => $modules,
            'faqs' => $faqs,
        ];

        $response = $this->actingAs($john->details)->json(
            'POST',
            'api/course',
            $payload
        );

        // $response->assertSee($associate->name);
        $response->assertSee('new category');

        $this->assertDatabaseHas('courses', [
            'title' => $firstCourse->title,
            'course_path_position' => 2,
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'course_path_position' => 1,
        ]);

        $response->assertJsonFragment([
            'message' => 'Successfully fetched course',
            // 'data' => [
                
            // ]
        ]);
        
        $response->assertStatus(200);
    }

    public function testValidation()
    {
        Instructor::unsetEventDispatcher();
        $john = factory(Instructor::class)->create();

        $response = $this->json(
            'POST',
            'api/course',
            [
            ]
        );

        $response->assertStatus(403);

        $response = $this->actingAs($john->details)->json(
            'POST',
            'api/course',
            [
            ]
        );

        $response->assertStatus(422);
    }

    public function testCanFetchACourse ()
    {
        $course = factory(Course::class)->create();
        $response = $this->json(
            'GET',
            'api/courses/'.$course->slug
        );

        $response->assertSee($course->title);
        $response->assertSee($course->description);
        $response->assertStatus(200);
        
    }
}