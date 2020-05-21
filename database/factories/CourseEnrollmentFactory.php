<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use App\CourseBatch;
use App\User;
use App\CourseEnrollment;
use Faker\Generator as Faker;

$factory->define(CourseEnrollment::class, function (Faker $faker, $attrib) {
    // $userIds = User::whereUserableType('App\Student')->get()->pluck('id')->toArray();

    if (!isset($attrib["course_batch_id"]) && !isset($attrib["course_id"]))
    {
        $courseBatch = factory(CourseBatch::class)->create();
        $courseBatch = factory(CourseBatch::class)->create();
        $course_batch_id = $courseBatch->id;
        $course_id = $courseBatch->course_id;
    } else {
        $course_batch_id = $attrib['course_batch_id'];
        $course_id = $attrib['course_id'];
    }

    return [
        'user_id' => 1,
        'active' => $faker->randomElement([0, 1]),
        'course_id' => $course_id,
        'expires_at' => now()->addMinutes(10),
        'course_batch_id' => $course_batch_id,
    ];
});
