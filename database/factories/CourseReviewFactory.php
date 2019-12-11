<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CourseReview;
use Faker\Generator as Faker;

$factory->define(CourseReview::class, function (Faker $faker) {
    $courseBatch = factory(CourseBatch::class)->create();
    return [
        'review_text' => $faker->paragraph,
        'course_id' => $courseBatch->course_id,
        'enrollee_id' => factory(Student::class)->create()->details->id,
        'approved' => $faker->randomElement([0, 1]),
        'rating' => $faker->numberBetween(0, 5)
    ];
});
