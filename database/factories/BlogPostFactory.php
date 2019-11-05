<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use App\BlogPost;
use App\Instructor;
use Faker\Generator as Faker;

$factory->define(BlogPost::class, function (Faker $faker) {

    Instructor::unsetEventDispatcher();
    $instructor = factory(Instructor::class)->create();

    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph(4),
        'author_id' => $instructor->details->id,
        'published_at' => Carbon\Carbon::now(),
        'published' => 1,
        'blog_image' => $faker->randomElement([
            'images/blogposts/post1.png',
            'images/blogposts/post2.png',
            'images/blogposts/post3.png',
            'images/blogposts/post4.png',
            'images/blogposts/post5.png',
            'images/blogposts/post6.png',
        ])
    ];
});

$factory->afterCreating(BlogPost::class, function($post, $faker) {
    $post->tags()->saveMany(factory(Tag::class, 4)->create());
});

