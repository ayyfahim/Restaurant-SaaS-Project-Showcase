<?php

use Illuminate\Database\Seeder;

class RazorpaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->where('id', '=', 12)->delete();
        \DB::table('settings')->where('id', '=', 13)->delete();
        \DB::table('settings')->where('id', '=', 14)->delete();



        \DB::table('settings')->insert(array (
            0 =>
                array (
                    'id' => 12,
                    'key'=> 'IsRazorpayPaymentEnabled',
                    'value' => '1',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),

            1 =>
                array (
                    'id' => 13,
                    'key'=> 'RazorpayKeyId',
                    'value' => 'pk_test_FkQvi0DNueKlNnVwNoJktg2W',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),
            2 =>
                array (
                    'id' => 14,
                    'key'=> 'RazorpayKeySecret',
                    'value' => 'sk_test_hPRNV2gZ6gcIV99ndFejwEHT',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),

        ));
    }
}
