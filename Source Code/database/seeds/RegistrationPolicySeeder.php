<?php

use Illuminate\Database\Seeder;

class RegistrationPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->where('id', '=', 18)->delete();
        \DB::table('settings')->where('id', '=', 19)->delete();
        \DB::table('settings')->where('id', '=', 20)->delete();

        \DB::table('settings')->insert(array(
            0 =>
            array(
                'id' => 18,
                'key' => 'SignupTermText',
                'value' => 'In order to register for our services you are required to register with our payments processor "Limonetic" in order to accept payments.',
                'created_at' => '2019-09-06 21:52:30',
                'updated_at' => '2019-09-06 21:52:30',
            ),

            1 =>
            array(
                'id' => 19,
                'key' => 'SignupTermFile',
                'value' => 'SignupTermFile.png',
                'created_at' => '2019-09-06 21:52:30',
                'updated_at' => '2019-09-06 21:52:30',
            ),
            2 =>
            array(
                'id' => 20,
                'key' => 'DocVerificationEmail',
                'value' => 'onboarding@appetizr.fr',
                'created_at' => '2019-09-06 21:52:30',
                'updated_at' => '2019-09-06 21:52:30',
            ),

        ));
    }
}
