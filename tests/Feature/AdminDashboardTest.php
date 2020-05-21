<?php

namespace Tests\Feature;

use App\Student;
use App\Instructor;
use Tests\TestCase;
use App\CourseEnrollment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanFetchDashboardStats()
    {
        // TODO: update test to allow only admin fetch this data
        // Instructor::unsetEventDispatcher();
        // Student::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        factory(Instructor::class, 11)->create();
        factory(Student::class, 3)->create();

        // factory(CourseEnrollment::class)->create([
        //     'active' => 1,
        //     // 'user_id' => $this->students['mazino']->details->id,
        //     // 'course_id' => $this->courses["course1"]->id,
        //     // 'course_batch_id' => $this->batches['batchA']->id
        // ]);

        $response = $this
        ->actingAs($john->details)
        ->json(
            'GET',
            '/api/dashboard-stats'
        );

        $response->assertJsonFragment([
            'message' => 'successfully fetched dashboard stats',
            'studentsCount' => 0,
            'coursesCount' => 0
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCannnotFetchDashboardStatsWithoutAuth()
    {
        $response = $this->json(
            'GET',
            '/api/dashboard-stats'
        );

        $response->assertSee("Unauthenticated.");
        $response->assertStatus(401);
    }
}
