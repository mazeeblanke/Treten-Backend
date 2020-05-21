<?php

namespace Tests\Feature;

use App\User;
use App\Course;
use App\Student;
use App\CoursePath;
use App\Instructor;
use Tests\TestCase;
use App\CourseBatch;
use App\CourseCategory;
use App\CourseBatchAuthor;
use App\CourseEnrollment;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursePathTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $courses = [];
    protected $batches = [];
    protected $students = [];
    protected $categories = [];
    protected $coursePaths = [];
    protected $instructors = [];

    protected function setUp (): void
    {
        parent::setUp();

        $this->createCategories()
            ->createCoursePaths()
            ->setupAdmin()
            ->setupInstructors()
            ->setupStudents()
            ->createCoursesAndAssignCategory()
            ->assignBatches()
            ->allocateInstructors()
            ->enrollAStudent();
    }

    public function testCanFetchAllCoursePathsOmittingNotPublishedPathsAndInstructorLessCourses (): void
    {
        $page = 1;
        $pageSize = 6;

        $response = $this->json(
            'GET',
            'api/course-paths',
            [
                "page" => $page,
                "pageSize" => $pageSize
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->coursePaths["coursePath 1"]->name);
        $response->assertSee($this->coursePaths["coursePath 2"]->name);
        $response->assertDontSee($this->coursePaths["coursePath 3"]->name);
        $response->assertDontSee($this->coursePaths["coursePath 4"]->name);
        $response->assertDontSee($this->coursePaths["coursePath 5"]->name);

        $response->assertJsonFragment(([
            "perPage" => $pageSize,
            "currentPage" => $page
        ]));

        $response->assertStatus(200);
    }

    protected function assertCourseJsonStructure ($response): void
    {
        $response->assertJsonStructure([
            "currentPage",
            "perPage",
            'message',
            "total",
            'data' => [
              '*' => [
                'name',
                'description',
              ]
            ]
          ]);
    }

    private function createCategories ()
    {
        $this->categories['associate'] = factory(CourseCategory::class)
            ->create([
                'name' => 'associate'
            ]);

        $this->categories['professional'] = factory(CourseCategory::class)
            ->create([
                'name' => 'professional'
            ]);

        $this->categories['expert'] = factory(CourseCategory::class)
            ->create([
                'name' => 'expert'
            ]);

        $this->categories['randomCategory1'] = factory(CourseCategory::class)
            ->create([
                'name' => 'category 1'
            ]);

        return $this;
    }

    private function setupAdmin ()
    {
        $roleAdmin = Role::firstOrCreate(
            ['name' => 'admin'],
            ['name' => 'admin']
        );

        $this->admin = factory(User::class)->create([
            'email' => 'admin@treten.com',
            'status' => 'active'
        ]);

        $this->admin->assignRole($roleAdmin);

        return $this;
    }

    private function setupInstructors ()
    {
        $this->instructors['annie'] = factory(Instructor::class)->create();

        $this->instructors['coker'] = factory(Instructor::class)->create();

        return $this;
    }

    private function setupStudents ()
    {
        $this->students['james'] = factory(Student::class)->create();

        $this->students['mazino'] = factory(Student::class)->create();

        return $this;
    }

    private function createCoursesAndAssignCategory ()
    {
        $courses = [1, 2, 3, 4, 5];
        $categories = [
            "associate",
            "professional",
            "randomCategory1",
            "expert",
            "expert",
        ];

        foreach ($courses as $key => $course)
        {
            $this->courses["course{$course}"] = factory(Course::class)->create([
                "is_published" => $key != 4 ? 1 : 0,
                "title" => "course {$course}",
                // "course_path_id" => $this->coursePaths["coursePath 1"]->id,
                "course_path_id" => $this->coursePaths["coursePath {$course}"]->id,
                "created_at" => now()->subDays($course)
            ]);

            $this->courses["course{$course}"]
                ->categories()
                ->attach([$this->categories[$categories[$key]]->id]);
        }

        return $this;
    }

    private function enrollAStudent ()
    {
        factory(CourseEnrollment::class)->create([
            'active' => 1,
            'user_id' => $this->students['mazino']->details->id,
            'course_id' => $this->courses["course1"]->id,
            'course_batch_id' => $this->batches['batchA']->id
        ]);

        return $this;
    }

    private function assignBatches ()
    {
        $courses = ['A' => 1, 'B' => 2, 'C' => 3];

        foreach ($courses as $key => $courseId)
        {
            $this->batches["batch{$key}"] = factory(CourseBatch::class)
                ->create([
                    "batch_name" => "batch{$key}",
                    "course_id" => $this->courses["course{$courseId}"],
                ]);
        }

        return $this;
    }

    private function createCoursePaths ()
    {
        $this->coursePaths['coursePath 1'] = factory(CoursePath::class)->create([
            'name' => 'Cisco Service Provider'
        ]);

        $this->coursePaths['coursePath 2'] = factory(CoursePath::class)->create([
            'name' => 'Cisco Security'
        ]);

        $this->coursePaths['coursePath 3'] = factory(CoursePath::class)->create([
            'name' => 'Cisco R&S'
        ]);

        $this->coursePaths['coursePath 4'] = factory(CoursePath::class)->create([
            'name' => 'Cisco Collaboration'
        ]);

        $this->coursePaths['coursePath 5'] = factory(CoursePath::class)->create([
            'name' => 'Cisco Datacenter'
        ]);

        $this->coursePaths['coursePath 6'] = factory(CoursePath::class)->create([
            'name' => 'Firewall Expert'
        ]);

        return $this;
    }

    private function allocateInstructors ()
    {
        $courses = ['A' => 1, 'B' => 2, 'C' => 3];
        $instructorIds = [
            $this->instructors['annie']->details->id,
            $this->instructors['coker']->details->id,
            $this->admin->id
        ];

        foreach ($courses as $key => $courseId)
        {
            $data = [
                'course_id' => $this->courses["course{$courseId}"]->id,
                'course_batch_id' => $this->batches["batch{$key}"]->id,
                'author_id' => $instructorIds[$courseId - 1],
            ];

            if (($courseId - 1) === 2)
            {
                $data = array_merge($data, [
                    'timetable' => []
                ]);
            }

            $this->batches["batch{$key}"] = factory(CourseBatchAuthor::class)
                ->create($data);

        }

        return $this;
    }
}
