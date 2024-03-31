<?php

use Illuminate\Database\Seeder;

class PrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->where('id', '=', 10)->delete();


        \DB::table('settings')->insert(array (
            0 =>
                array (
                    'id' => 10,
                    'key'=> 'PrivacyPolicy',
                    'value' => 'sample privacy policy text',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),

        ));
    }
}
