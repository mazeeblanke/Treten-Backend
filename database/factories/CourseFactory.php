<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use App\CourseCategory;
use App\CoursePath;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {

    $faqs = '[{"question":"DO YOU OFFER CCIE COURSES IN NIGERIA?","answer":" Yes we do. Please give us a call to dicsuss further."},{"question":"CAN I JOIN THE CLASSES REMOTELY","answer":"Yes you can. You can enjoy from the comfort of your home or office."},{"question":"HOW ARE COURSES TAUGHT?","answer":" Classes fully adhere to international syllables and guidelines set by the certifications providers."},{"question":"DO I GET A CERTIFICATE AFTER PARTICIPATION IN THE TRAINING?","answer":"The trainer will hand out a certificate on the last day of the training if it has been completed (at least 75% attendance)."}]';

    $modules = '[{"name":"Module 1","description":"Module 1 description"},{"name":"Module 2","description":"Module 2 description"},{"name":"Module 3","description":"Module 3 description"},{"name":"Module 4","description":"Module 4 description"}]';

    return [
        'title' => $faker->name,
        'description' => $faker->paragraph,
        'price' => $faker->randomNumber(2),
        'is_published' => $faker->randomElement([0, 1]),
        'banner_image' => $faker->randomElement([
            'courses/course1.png',
            'courses/course2.png',
            'courses/course3.png',
            'courses/course4.png',
            'courses/course5.png',
        ]),
        'duration' => 10,
        "video_id" => '',
        'author_id' => 1,
        'course_path_id' => factory(CoursePath::class)->create()->id,
        'course_path_position' => 1,
        'modules' => $modules,
        'faqs' => $faqs,
        'created_at' => $faker->datetime
    ];
});

$factory->afterCreating(Course::class, function($course, $faker) {
    // CoursePath::unsetEventDispatcher();
    // $category = $faker->randomElement([
    //     1,
    //     2,
    //     3
    // ]);
    // $course->categories()->attach([$category]);
});
