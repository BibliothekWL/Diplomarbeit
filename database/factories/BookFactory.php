<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->text(30),
        'systematik' => $faker->text(30),
        'medium' => $faker->text(30),
        'content' => $faker->text(200),
        'BNR' => $faker->numberBetween(0,99999),
        'created_at' => now(),
        'updated_at' => now(),
        'author_id' => 1,
        'user_id' => 0
    ];
});
