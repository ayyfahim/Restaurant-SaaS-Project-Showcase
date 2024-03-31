<?php

use Illuminate\Database\Seeder;

class StoreSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\StoreSubscription::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        \App\Models\StoreSubscription::create([
            'name' => 'Delivery/Takeway',
            'price' => 0,
            'description' => 'Delivery/Takeway Package',
            'days' => 30,
            'is_active' => 1,
            'is_one_time' => 0,
        ]);

        \App\Models\StoreSubscription::create([
            'name' => 'In House',
            'price' => 0,
            'description' => 'In House Package',
            'days' => 30,
            'is_active' => 1,
            'is_one_time' => 0,
        ]);

        \App\Models\StoreSubscription::create([
            'name' => 'Full Package',
            'price' => 0,
            'description' => 'Full Package Package',
            'days' => 30,
            'is_active' => 1,
            'is_one_time' => 0,
        ]);
    }
}
