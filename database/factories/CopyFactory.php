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

$factory->define(App\Models\Copy::class, function (Faker $faker) {
    return [
        'label_id' => App\Models\Label::all()->random()->id,
        'quantity' => $faker->numberBetween($min = 1000, $max = 10000),
        'models' => $faker->numberBetween($min = 1, $max = 5),
        'plates' => $faker->numberBetween($min = 1, $max = 3),
        'price' => $faker->randomFloat($nbMaxDecimals = 4, $min = 100, $max = 10000),
        'shipping' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 60),
        'vat' => $faker->randomFloat($nbMaxDecimals = 4, $min = 10, $max = 10000),
    ];
});
