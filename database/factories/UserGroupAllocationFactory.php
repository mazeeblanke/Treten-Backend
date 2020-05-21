<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserGroupAllocation;
use Faker\Generator as Faker;

$factory->define(UserGroupAllocation::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement([1, 2, 3, 4, 5, 6]),
        'user_group_id' => $faker->randomElement([1, 2, 3]),
    ];
});
