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

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $courses = [];
    protected $batches = [];
    protected $students = [];
    protected $categories = [];
    protected $instructors = [];

    protected function setUp (): void
    {
        parent::setUp();
      
        $this->createCategories()
            ->setupAdmin()
            ->setupInstructors()
            ->setupStudents()
            ->createCoursesAndAssignCategory()
            ->assignBatches()
            ->allocateInstructors()
            ->enrollAStudent();
    }

    public function testCanCreateaCourse(): void
    {
        $faqs = '[{"question":"DO YOU OFFER CCIE COURSES IN NIGERIA?","answer":" Yes we do. Please give us a call to dicsuss further."},{"question":"CAN I JOIN THE CLASSES REMOTELY","answer":"Yes you can. You can enjoy from the comfort of your home or office."},{"question":"HOW ARE COURSES TAUGHT?","answer":" Classes fully adhere to international syllables and guidelines set by the certifications providers."},{"question":"DO I GET A CERTIFICATE AFTER PARTICIPATION IN THE TRAINING?","answer":"The trainer will hand out a certificate on the last day of the training if it has been completed (at least 75% attendance)."}]';

        $modules = '[{"name":"Module 1","description":"Module 1 description"},{"name":"Module 2","description":"Module 2 description"},{"name":"Module 3","description":"Module 3 description"},{"name":"Module 4","description":"Module 4 description"}]';

        $this->categories['associate'] = factory(CourseCategory::class)
            ->create([
                'name' => 'associate'
            ]);

        $ccna = factory(CoursePath::class)->create(['name' => 'ccna']);

        $firstCourse = factory(Course::class)->create([
            'course_path_id' => $ccna->id,
            'author_id' => $this->admin->id,
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

        $response = $this->actingAs($this->admin)->json(
            'POST',
            'api/course',
            $payload
        );

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
        ]);
        
        $response->assertStatus(200);
    }

    public function testValidation(): void
    {
        Instructor::unsetEventDispatcher();
        $john = factory(Instructor::class)->create();

        $response = $this->json(
            'POST',
            'api/course',
            []
        );

        $response->assertStatus(403);

        $response = $this->actingAs($john->details)->json(
            'POST',
            'api/course',
            []
        );

        $response->assertStatus(422);
    }

    public function testCanFetchACourse (): void
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

    public function testCanFetchAllCourses (): void
    {
        $page = 1;
        $pageSize = 2;

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => $page,
                "pageSize" => $pageSize
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);

        $response->assertJsonFragment(([
            "perPage" => $pageSize,
            "currentPage" => $page
        ]));

        $response->assertStatus(200);
    }

    public function testCanFetchAndFilterCoursesByCategory (): void
    {
        $page = 1;
        $pageSize = 12;
        $category = 'expert';

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => $page,
                "pageSize" => $pageSize,
                "category" => $category,
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertJsonCount(2, 'data');

        $response->assertJsonFragment(([
            "perPage" => $pageSize,
            "currentPage" => $page
        ]));

        $response->assertStatus(200);
    }

    public function testCanFetchAndFilterCoursesByTitle (): void
    {
        $page = 1;

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => $page,
                "q" => "course 1"
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertJsonCount(1, 'data');

        $response->assertStatus(200);
    }

    public function testCanFetchAndFilterCoursesByPublishedStatus (): void 
    {
        $page = 1;

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => $page,
                "isPublished" => 0
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course5"]->title);
        $response->assertDontSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertJsonCount(1, 'data');

        $response->assertStatus(200);
    }

    public function testCanFetchAndFilterCoursesByHasInstructorWhenTrue (): void
    {
        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => 1,
                "hasInstructor" => 1
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);
        $response->assertJsonCount(2, 'data');
        $response->assertStatus(200);

    }

    public function testCanFetchAndFilterCoursesByHasInstructorWhenFalse (): void
    {

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => 1,
                "hasInstructor" => 0
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertDontSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertSee($this->courses["course3"]->title);
        $response->assertSee($this->courses["course4"]->title);
        $response->assertSee($this->courses["course5"]->title);
        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
    }

    public function testCanFetchAndFilterCoursesByAuthorIdUsingId (): void
    {

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => 1,
                "authorId" => $this->instructors['coker']->details->id
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertDontSee($this->courses["course1"]->title);
        $response->assertSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);

    }

    public function testCanFetchAndFilterCoursesByAuthorIdUsingName (): void
    {

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => 1,
                "authorId" => $this->instructors['annie']->details->name
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);

    }

    public function testCanFetchAndFilterCoursesByCategoryId (): void
    {
        $page = 1;

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => $page,
                "categoryId" => $this->categories['associate']->id
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);
    }

    public function testCanFetchAndFilterCoursesByEnrolledWhenTrueWithoutLoggedInUser (): void
    {
        $page = 1;

        $response = $this->json(
            'GET',
            'api/courses',
            [
                "page" => $page,
                "enrolled" => 1
            ]
        );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertSee($this->courses["course2"]->title);
        $response->assertSee($this->courses["course3"]->title);
        $response->assertSee($this->courses["course4"]->title);
        $response->assertSee($this->courses["course5"]->title);
        $response->assertJsonCount(5, 'data');
        $response->assertStatus(200);

    }

    public function testCanFetchAndFilterCoursesByEnrolledWhenFalseWithLoggedInUser (): void
    {
        $page = 1;

        $response = $this
            ->actingAs($this->students['mazino']->details)
            ->json(
                'GET',
                'api/courses',
                [
                    "page" => $page,
                    "enrolled" => 0
                ]
            );

        $this->assertCourseJsonStructure($response);
        $response->assertDontSee($this->courses["course1"]->title);
        $response->assertSee($this->courses["course2"]->title);
        $response->assertSee($this->courses["course3"]->title);
        $response->assertSee($this->courses["course4"]->title);
        $response->assertSee($this->courses["course5"]->title);
        $response->assertJsonCount(4, 'data');
        $response->assertStatus(200);

    }

    public function testCanFetchAndFilterCoursesByEnrolledWhenTrueWithLoggedInUser (): void
    {
        $page = 1;
 
        $response = $this
            ->actingAs($this->students['mazino']->details)
            ->json(
                'GET',
                'api/courses',
                [
                    "page" => $page,
                    "enrolled" => 1
                ]
            );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);

    }

    public function testCanFetchAndFilterCoursesAssignedToAnInstructor (): void
    {
        $page = 1;
 
        $response = $this
            ->actingAs($this->students['mazino']->details)
            ->json(
                'GET',
                'api/courses',
                [
                    "page" => $page,
                    "authorId" => $this->instructors['annie']->details->id,
                    "notAssigned" => 0
                ]
            );

        $this->assertCourseJsonStructure($response);
        $response->assertSee($this->courses["course1"]->title);
        $response->assertDontSee($this->courses["course2"]->title);
        $response->assertDontSee($this->courses["course3"]->title);
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);

    }

    public function testCanFetchAndFilterCoursesNotAssignedToAnInstructor (): void
    {
        $page = 1;
 
        $response = $this
            ->actingAs($this->students['mazino']->details)
            ->json(
                'GET',
                'api/courses',
                [
                    "page" => $page,
                    "authorId" => $this->instructors['annie']->details->id,
                    "notAssigned" => 1
                ]
            );

        $this->assertCourseJsonStructure($response);
        $response->assertDontSee($this->courses["course1"]->title);
        $response->assertSee($this->courses["course2"]->title);
        $response->assertSee($this->courses["course3"]->title);
        
        //dont see these two because they have not been even assigned a batch on the `course_batch_author` table
        $response->assertDontSee($this->courses["course4"]->title);
        $response->assertDontSee($this->courses["course5"]->title);
        $response->assertJsonCount(2, 'data');
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
                'id',
                'slug',
                'faqs',
                'price',
                'title',
                'author',
                'modules',
                'category',
                'duration',
                'avgRating',
                'isPublished',
                'description',
                'publishedAt',
                'instructors',
                'transaction',
                'courseReview',
                'learnersCount',
                'coursePathId',
                'courseReviews',
                'relatedCourses',
                'certificationBy',
                'instructorReviews',
                'coursePathPosition',
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