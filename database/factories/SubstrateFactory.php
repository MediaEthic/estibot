<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Substrate::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'weight' => $faker->numberBetween($min = 55, $max = 150),
//        'thickness' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0.5, $max = 1.5),
        'width' => $faker->numberBetween($min = 25, $max = 304),
//        'length' => $faker->numberBetween($min = 64500, $max = 96900),
        'price' => $faker->randomFloat($nbMaxDecimals = 4, $min = 100, $max = 800),
    ];
});
