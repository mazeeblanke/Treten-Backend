<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use App\CourseBatch;
use Faker\Generator as Faker;

$factory->define(CourseBatch::class, function (Faker $faker) {
    $coursesIds = Course::all()->pluck('id')->toArray();
    return [
        'batch_name' => $faker->randomElement([
            'Batch B 2019',
            'Batch C 2020',
            'Batch D 2019',
            'Batch E 2019',
            'Batch A 2020',
            'Batch G 2019',
            'Batch D 2020',
            'Batch A 2022',
            'Batch V 2019',
            'Batch X 2019',
            'Batch G 2019',
            'Batch K 2019',
            'Batch S 2019',
            'Batch P 2019',
        ]),
        'price' => $faker->randomNumber(4),
        'start_date' => \Carbon\Carbon::now()->format('Y-m-d'),
        'mode_of_delivery' => $faker->randomElement([
            'on site',
            'on demand',
            'remote'
        ]),
        'course_id' => $faker->randomElement($coursesIds),
        // 'course_id' => factory(Course::class)->create()->id,
        // 'instructor_id' => $faker->randomElement([1, 2, 3, 4, 5, 6]),
    ];
});