<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;
use App\User;


$factory->define(Student::class, function (Faker $faker) {
    return [
        'best_instructor' => $faker->firstName
    ];
});


$factory->afterCreating(Student::class, function ($student, $faker) {
    $user = factory(User::class)->make();
    $user->unsetEventDispatcher();
    $student->details()->save($user);
});
