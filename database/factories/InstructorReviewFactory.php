<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CourseBatch;
use App\InstructorReview;
use App\Student;
use Faker\Generator as Faker;

$factory->define(InstructorReview::class, function (Faker $faker) {
    $courseBatch = factory(CourseBatch::class)->create();
    return [
        'review_text' => $faker->paragraph,
        'course_id' => $courseBatch->course_id,
        'course_batch_id' => $courseBatch->id,
        'approved' => $faker->randomElement([0, 1]),
        'enrollee_id' => factory(Student::class)->create()->details->id,
        'author_id' => $faker->randomElement([
            1, 2, 3, 5, 6, 4
        ]),
        'rating' => $faker->numberBetween(0, 5)
    ];
});
