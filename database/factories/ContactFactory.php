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

$factory->define(App\Models\Contact::class, function (Faker $faker) {
    return [
        'third_id' => App\Models\Third::all()->random()->id,
        'civility' => $faker->randomElement(['Mr', 'Mrs']),
        'name' => $faker->name,
        'surname' => $faker->name,
        'profession' => $faker->asciify('**********'),
        'service' => $faker->asciify('**********'),
        'email' => $faker->email,
        'mobile' => $faker->randomDigit,
        'phone' => $faker->randomDigit,
    ];
});
