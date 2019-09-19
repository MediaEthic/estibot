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

$factory->define(App\Models\Quotation::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => Str::random(100),
        'image' => $faker->randomElement(["undraw_Credit_card_3ed6.svg", "undraw_make_it_rain_iwk4.svg", "undraw_printing_invoices_5r4r.svg", "undraw_Savings_dwkw.svg"]),
        'third_id' => App\Models\Third::all()->random()->id,
        'label_id' => App\Models\Label::all()->random()->id,
        'delivery_date' => $faker->dateTimeThisYear('+1 month'),
        'validity' => $faker->dateTimeThisYear('+1 month'),
        'thousand' => $faker->randomFloat($nbMaxDecimals = 4, $min = 100, $max = 10000),
        'shipping' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 60),
        'vat' => $faker->randomFloat($nbMaxDecimals = 4, $min = 10, $max = 10000),
    ];
});
