<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Instructor;
use Faker\Generator as Faker;
use App\User;

$factory->define(Instructor::class, function (Faker $faker) {
    return [
        'consideration' => $faker->paragraph,
        'qualifications' => $faker->sentence,
        'social_links' => serialize([
            'facebook' => 'facebook.com/maz',
            'twitter' => 'twitter.com/maz',
            'instagram' => 'instagram.com/maz'
        ]),
        'title' => 'Head of Enginnering'
    ];
});


$factory->afterCreating(Instructor::class, function($instructor, $faker) {
    User::unsetEventDispatcher();
    $instructor->details()->save(factory(User::class)->make());
});
