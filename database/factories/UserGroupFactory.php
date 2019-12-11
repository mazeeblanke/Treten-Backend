<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserGroup;
use Faker\Generator as Faker;

$factory->define(UserGroup::class, function (Faker $faker) {
    return [
        'group_name' => $faker->name,
        'group_description' => $faker->paragraph(3)
    ];
});
