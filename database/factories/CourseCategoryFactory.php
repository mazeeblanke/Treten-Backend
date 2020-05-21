<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CourseCategory;
use Faker\Generator as Faker;

$factory->define(CourseCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph
    ];
});