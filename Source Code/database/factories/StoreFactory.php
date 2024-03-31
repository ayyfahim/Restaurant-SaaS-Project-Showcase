<?php

/** @var Factory $factory */

use App\Models\Store;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Store::class, function (Faker $faker) {
    return [
        'email' => 'store@demo.com',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
    ];
});
