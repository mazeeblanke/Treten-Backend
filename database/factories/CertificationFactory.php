<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Certification;
use Faker\Generator as Faker;

$factory->define(Certification::class, function (Faker $faker) {
    return [
        'company' => $faker->name,
        'banner_image' => ''
    ];
});
