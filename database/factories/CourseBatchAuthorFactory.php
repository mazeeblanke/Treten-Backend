<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\CourseBatch;
use App\CourseBatchAuthor;
use Faker\Generator as Faker;

$factory->define(CourseBatchAuthor::class, function (Faker $faker) {
    $instructorIds = User::whereUserableType('App\Instructor')->get()->pluck('id')->toArray();
    $courseBatch = factory(CourseBatch::class)->create();
    $timetable = [
        [
            'day' => 'mondays',
            'sessions' => [
                [
                    'activityName' => 'Morning class',
                    'begin' => '12:34',
                    'end' => '2:34',
                ],
                [
                    'activityName' => 'Afternoon class',
                    'begin' => '1:34',
                    'end' => '20:34',
                ],
            ],
        ],
        [
            'day' => 'wednesdays',
            'sessions' => [
                [
                    'activityName' => 'Morning class',
                    'begin' => '8:34',
                    'end' => '12:34',
                ],
                [
                    'activityName' => 'Afternoon class',
                    'begin' => '1:34',
                    'end' => '2:34',
                ],
            ],
        ],
    ];
    return [
        'course_batch_id' => $courseBatch->id,
        'author_id' => $faker->randomElement($instructorIds),
        'course_id' => $courseBatch->course_id,
        'timetable' => $timetable,
        // 'instructor_id' => factory(Instructor::class)->create()->id,
    ];
});