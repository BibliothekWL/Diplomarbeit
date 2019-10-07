<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->paragraph,
        'systematik' => $faker->paragraph,
        'medium' => $faker->paragraph,
        'content' => $faker->paragraph,
        'BNR' => $faker->numberBetween(0,99999),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
