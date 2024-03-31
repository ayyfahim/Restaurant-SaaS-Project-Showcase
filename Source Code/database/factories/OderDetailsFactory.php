<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OderDetails;
use Faker\Generator as Faker;

$factory->define(OderDetails::class, function (Faker $faker) {
    $sub_total = rand(10, 500);
    $quantity = rand(2, 15);

    return [
        'kitchen_location_id' => 1,
        'status' => 0,
        'name' => \App\Product::all()->random()->name,
        'price' => $sub_total,
        'quantity' => $quantity,
    ];
});
