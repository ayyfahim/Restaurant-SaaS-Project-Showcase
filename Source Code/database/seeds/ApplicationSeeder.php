<?php

use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('applications')->insert(array(
            0 =>
            array(
                'id' => 11,
                'application_name' => 'Appetizr',
                'application_email' => 'appetizr@mail.com',
                'application_logo' => 'storage/account/RQTeFWSX7IVt16DOvolskz8tXg8EaEXAvcXTJQFt.png',
                'currency_symbol' => '€',
                'contact_no' => '1234567890',
                'Address' => 'nrwfsf',
                'currency_symbol_location' => 'left',
                'created_at' => now(),
                'updated_at' => now(),
            ),

        ));
    }
}
