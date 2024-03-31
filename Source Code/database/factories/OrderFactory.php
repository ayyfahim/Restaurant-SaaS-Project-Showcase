<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $sub_total = rand(10, 5000);
    // $created_at =  Carbon::create(rand(2020, 2021), rand(1, 12), rand(1, 30));

    return [
        'order_unique_id' => "ODR-" . time(),
        'store_id' => 1,
        'table_no' => \App\Models\Table::all()->random()->id,
        'customer_id' => \App\Models\Customer::all()->random()->id,
        'customer_name' => \App\Models\Customer::all()->random()->name,
        'customer_phone' => \App\Models\Customer::all()->random()->phone,
        'sub_total' => $sub_total,
        'discount' => 0,
        'tax' => 0,
        'store_charge' => 0,
        'total' => $sub_total,
        'paid_amount' => $sub_total,
        'is_paid' => 1,
        'status' => 2,
        'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null)
    ];
});
