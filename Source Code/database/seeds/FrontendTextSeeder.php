<?php

use Illuminate\Database\Seeder;

class FrontendTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->where('id', '=', 15)->delete();
        \DB::table('settings')->where('id', '=', 16)->delete();
        \DB::table('settings')->where('id', '=', 17)->delete();



        \DB::table('settings')->insert(array(
            0 =>
            array(
                'id' => 15,
                'key' => 'Abouts',
                'value' => '<p>sample text about updated2</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ),

            1 =>
            array(
                'id' => 16,
                'key' => 'TermsandCondition',
                'value' => '<p>sample text TermsandCondition&nbsp;updated3</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 =>
            array(
                'id' => 17,
                'key' => 'Refund',
                'value' => '<p>sample text refund updated4</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ),

        ));
    }
}
