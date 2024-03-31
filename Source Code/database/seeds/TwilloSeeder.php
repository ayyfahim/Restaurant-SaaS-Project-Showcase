<?php

use Illuminate\Database\Seeder;

class TwilloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('settings')->where('id', '=', 5)->delete();
        \DB::table('settings')->where('id', '=', 6)->delete();
        \DB::table('settings')->where('id', '=', 7)->delete();
        \DB::table('settings')->where('id', '=', 8)->delete();
        \DB::table('settings')->where('id', '=', 9)->delete();
        \DB::table('settings')->where('id', '=', 11)->delete();


        \DB::table('settings')->insert(array (
            0 =>
                array (
                    'id' => 5,
                    'key'=> 'IsSandBoxEnabled',
                    'value' => '1',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),

            1 =>
                array (
                    'id' => 6,
                    'key'=> 'PhoneCode',
                    'value' => '+14155238886',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),
            2 =>
                array (
                    'id' => 7,
                    'key'=> 'SandBoxID',
                    'value' => 'AC6122b6aa2b2e8e1145fd160a5b33a897',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),
            3 =>
                array (
                    'id' => 8,
                    'key'=> 'SandBoxToken',
                    'value' => 'a2159a513c58ba5101496a8192b3c959',
                    'created_at' => '2019-09-06 21:52:30',
                    'updated_at' => '2019-09-06 21:52:30',
                ),
            4 =>
                array (
                    'id' => 9,
                    'key'=> 'IsStoreEnabled',
                    'value' => '1',
                    'created_at' => '2020-10-06 21:52:30',
                    'updated_at' => '2020-10-06 21:52:30',
                ),
            5 =>
                array (
                    'id' => 11,
                    'key'=> 'SandboxTrialText',
                    'value' => 'join tongue-getting',
                    'created_at' => '2020-10-06 21:52:30',
                    'updated_at' => '2020-10-06 21:52:30',
                ),



        ));
    }
}
