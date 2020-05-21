<?php

namespace Tests\Feature;

use App\Course;
use App\CourseBatch;
use App\CourseBatchAuthor;
use App\Instructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseBatchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanCreateCourseBatch()
    {
        // Instructor::unsetEventDispatcher();
        $john = factory(Instructor::class)->create();

        $course = factory(Course::class)->create();

        $payload = [
            'batchName' => 'Batch B 2019',
            'commencementDate' => \Carbon\Carbon::now()->format('Y-m-d'),
            'modeOfDelivery' => 'on site',
            'courseId' => $course->id,
            'timetable' => [
                [
                    'day' => 'mondays',
                    'sessions' => [
                        [
                            'activityName' => 'balh',
                            'begin' => '12:34',
                            'end' => '2:34',
                        ],
                        [
                            'activityName' => 'hass',
                            'begin' => '1:34',
                            'end' => '20:34',
                        ],
                    ],
                ],
                [
                    'day' => 'wednesdays',
                    'sessions' => [
                        [
                            'activityName' => 'sett',
                            'begin' => '8:34',
                            'end' => '12:34',
                        ],
                        [
                            'activityName' => 'opq',
                            'begin' => '9:34',
                            'end' => '2:34',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($john->details)->json(
            'POST',
            'api/course-batches',
            $payload
        );

        // dd($response->getContent());

        // $response->assertJsonFragment([
        //     [
        //         'day' => 'mondays',
        //         'sessions' => [
        //             [
        //                 'activityName' => 'balh',
        //                 'begin' => '12:34',
        //                 'end' => '2:34',
        //             ],
        //             [
        //                 'activityName' => 'hass',
        //                 'begin' => '1:34',
        //                 'end' => '20:34',
        //             ],
        //         ],
        //     ],
        //     [
        //         'day' => 'wednesdays',
        //         'sessions' => [
        //             [
        //                 'activityName' => 'sett',
        //                 'begin' => '8:34',
        //                 'end' => '12:34',
        //             ],
        //             [
        //                 'activityName' => 'opq',
        //                 'begin' => '9:34',
        //                 'end' => '2:34',
        //             ],
        //         ],
        //     ],
        // ]);

        $this->AssertDatabaseHas('course_batches', [
            'batch_name' => $payload['batchName'],
            'mode_of_delivery' => $payload['modeOfDelivery'],
            'course_id' => $course->id,
        ]);

        $response->assertStatus(200);
    }

    public function testCreateCourseBatchValidation()
    {
        // Instructor::unsetEventDispatcher();
        $john = factory(Instructor::class)->create();

        // $response = $this->json(
        //     'POST',
        //     'api/course',
        //     [
        //     ]
        // );

        // $response->assertStatus(403);

        $response = $this->actingAs($john->details)->json(
            'POST',
            'api/course-batches',
            [
            ]
        );

        $response->assertStatus(422);
    }

    public function testCanFetchCourseBatches()
    {
        $john = factory(Instructor::class)->create();

        $course = factory(Course::class)->create();

        $courseBatches = factory(CourseBatch::class, 10)->create([
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($john->details)->json(
            'GET',
            'api/course-batches?courseId=' . $course->id
        );

        $response->assertJsonFragment([
            // 'data' => $courseBatches,
            'message' => 'Successfully fetched course batches',
        ]);

        $response->assertStatus(200);
    }


    public function testCanDeleteCourseBatch()
    {
        // Instructor::unsetEventDispatcher();
        $john = factory(Instructor::class)->create();

        $course = factory(Course::class)->create();

        $courseBatches = factory(CourseBatch::class, 10)->create([
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($john->details)->json(
            'DELETE',
            'api/course-batch/' . $courseBatches[0]->id
        );

        $response->assertDontSee($courseBatches[0]->batch_name);

        $response->assertJsonFragment([
            // 'data' => $courseBatches,
            'message' => 'Succesfully deleted',
        ]);

        $response->assertStatus(200);
    }


    public function testCanUpdateCourseBatch()
    {
        // Instructor::unsetEventDispatcher();
        $john = factory(Instructor::class)->create();

        $course = factory(Course::class)->create();

        $courseBatches = factory(CourseBatch::class, 10)->create([
            'course_id' => $course->id,
        ]);

        $courseBatchAuthor = factory(CourseBatchAuthor::class)->create([
            'course_batch_id' => $courseBatches[0]->id,
            'course_id' => $course->id
        ]);

        $response = $this->actingAs($john->details)->json(
            'POST',
            'api/course-batch/' . $courseBatches[0]->id,
            [
                'batchName' => 'mazee baby'
            ]
        );

        $response->assertSee('mazee baby');

        $response->assertJsonFragment([
            // 'data' => $courseBatches,
            'message' => 'Successfully updated course batch',
        ]);

        $response->assertStatus(200);
    }
}
