<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    // Student::unsetEventDispatcher();
    return [
        'message_uuid' => $faker->uuid,
        'sender_id' => $faker->randomElement([
            '1',
            '2',
            factory(Student::class)->create()->id,
        ]),
        'receiver_id' => $faker->randomElement([
            '1',
            '2',
            factory(Student::class)->create()->id,
        ]),
        'message_type' => $faker->randomElement([
            'chat',
            'broadcast',
        ]),
        'title' => $faker->sentence,
        'message' => $faker->sentence,
        'hash' => $faker->md5,
        'read' => $faker->randomElement([
            true,
            false,
        ]),
        'created_at' => Carbon\Carbon::now()
    ];
});
