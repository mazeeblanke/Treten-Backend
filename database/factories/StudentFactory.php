<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Student;
use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;


$factory->define(Student::class, function (Faker $faker) {
    return [
        'best_instructor' => $faker->firstName
    ];
});


$factory->afterCreating(Student::class, function ($student, $faker) {
    $role = Role::firstOrCreate(['name' => 'student'], ['name' => 'student']);
    $user = factory(User::class)->make();
    // $user->unsetEventDispatcher();
    $user->assignRole($role);
    $student->details()->save($user);
});
