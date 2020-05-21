<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CoursePath;
use Faker\Generator as Faker;

$factory->define(CoursePath::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'banner_image' => $faker->randomElement([
            'courses/course1.png',
            'courses/course2.png',
            'courses/course3.png',
            'courses/course4.png',
            'courses/course5.png',
        ]),
    ];
});