<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RoomAndBoard;
use Faker\Generator as Faker;

$factory->define(RoomAndBoard::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'code_claim' => $faker->word,
        'time_start' => $faker->word,
        'time_end' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'created_user' => $faker->randomDigitNotNull,
        'updated_user' => $faker->randomDigitNotNull
    ];
});
