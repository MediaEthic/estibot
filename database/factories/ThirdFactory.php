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

$factory->define(App\Models\Third::class, function (Faker $faker) {
    return [
        'alias' => $faker->asciify('*******'),
        'name' => $faker->name,
        'address_line2' => Str::random(25),
        'zipcode' => $faker->randomDigit,
        'city' => Str::random(10),
    ];
});
