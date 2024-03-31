<?php

use Illuminate\Database\Seeder;

class FactoryTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = factory(App\Models\Order::class, 10)
            ->create()
            ->each(function ($order) {
                // $OrderDetail = factory(App\Models\OrderDetails::class)->make();

                $OrderDetail = factory(App\Models\OrderDetails::class)->create([
                    'kitchen_location_id' => 1,
                    'status' => 0,
                    'name' => \App\Product::all()->random()->name,
                    'price' => rand(10, 500),
                    'quantity' => rand(2, 15),
                    'order_id' => $order->id,
                    'created_at' => $order->created_at
                ])
                    ->each(function ($detail) {
                        $OrderDetail = factory(App\Models\OrderDetailAddon::class, rand(1, 9))->create([
                            'kitchen_location_id' => 1,
                            'status' => 0,
                            'addon_id' => \App\Models\Addon::all()->random()->id,
                            'addon_name' => \App\Models\Addon::all()->random()->addon_name,
                            'addon_price' => \App\Models\Addon::all()->random()->price,
                            'addon_count' => rand(1, 20),
                            // 'quantity' => rand(2, 15),
                            'order_detail_id' => $detail->id,
                            'created_at' => $detail->created_at
                        ]);
                    });

                // $user->entities()->save($entity);

                // $order->orderDetails()->saveMany(
                //     factory(App\Models\OrderDetails::class, 10)
                //         ->make()
                // );
            });
    }
}
