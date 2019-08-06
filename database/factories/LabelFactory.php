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

$factory->define(App\Models\Label::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'width' => $faker->numberBetween($min = 35, $max = 105),
        'length' => $faker->numberBetween($min = 25, $max = 148.5),
        'substrate_id' => App\Models\Substrate::all()->random()->id,
        'winding' => $faker->randomElement(['ihead', 'ifoot', 'iright', 'ileft', 'ehead', 'efoot', 'eright', 'eleft']),
    ];
});
