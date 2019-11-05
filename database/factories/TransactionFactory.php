<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use Faker\Generator as Faker;


$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'invoice_id' => $faker->md5,
        'name' => $faker->name,
        'email' => $faker->email,
        'amount' => '$250,000',
        'user_id' => $faker->randomDigit,
        'course_batch_id' => $faker->randomDigit,
        'course_id' => $faker->randomDigit,
        'description' => $faker->sentence,
        'transaction_id' => $faker->md5,
        'authentication_code' => $faker->sha1,
        'status' => $faker->randomElement([
            'pending',
            'failed',
            'success'
        ]),
    ];
});
