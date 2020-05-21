<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Testimonial;
use Faker\Generator as Faker;

$factory->define(Testimonial::class, function (Faker $faker) {
    return [
        'text' => $faker->paragraph(2),
        'profile_pic' => '/static/images/student.png',
        'role' => 'student',
        'name' => $faker->name
    ];
});
