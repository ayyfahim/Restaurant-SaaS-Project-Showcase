<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ApplicationSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(TwilloSeeder::class);
        $this->call(PrivacyPolicySeeder::class);
        $this->call(RazorpaySeeder::class);
        $this->call(FrontendTextSeeder::class);
        $this->call(TranslationSeeder::class);
        $this->call(StoreSubscriptionSeeder::class);
        $this->call(RegistrationPolicySeeder::class);
        $this->call(RolePermissionSeeder::class);
    }
}
