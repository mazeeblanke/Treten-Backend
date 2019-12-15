<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Instructor;
use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;

$factory->define(Instructor::class, function (Faker $faker) {
    return [
        'consideration' => $faker->paragraph,
        'qualifications' => $faker->sentence,
        'social_links' => [
            'facebook' => '',
            'twitter' => '',
            'instagram' => ''
        ],
        'title' => 'Head of Enginnering'
    ];
});


$factory->afterCreating(Instructor::class, function($instructor, $faker) {
    $role = Role::firstOrCreate(['name' => 'instructor'], ['name' => 'instructor']);
    $user = factory(User::class)->make([
        'first_name' => $faker->firstName
    ]);
    // $user->unsetEventDispatcher();
    $user->assignRole($role);
    $instructor->details()->save($user);
});