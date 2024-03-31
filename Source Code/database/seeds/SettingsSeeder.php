<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('settings')->where('id', '=', 1)->delete();
        \DB::table('settings')->where('id', '=', 2)->delete();
        \DB::table('settings')->where('id', '=', 3)->delete();
        \DB::table('settings')->where('id', '=', 4)->delete();


        \DB::table('settings')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'key'=> 'IsStripePaymentEnabled',
                    'value' => '1',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),

            1 =>
                array (
                    'id' => 2,
                    'key'=> 'Currency',
                    'value' => 'inr',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),
            2 =>
                array (
                    'id' => 3,
                    'key'=> 'StripePublishableKey',
                    'value' => 'pk_test_FkQvi0DNueKlNnVwNoJktg2W',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),
            3 =>
                array (
                    'id' => 4,
                    'key'=> 'StripeSecretKey',
                    'value' => 'sk_test_hPRNV2gZ6gcIV99ndFejwEHT',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),

        ));
    }
}
