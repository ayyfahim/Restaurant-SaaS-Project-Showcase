<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Firebase\FirebaseUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Illuminate\Support\Facades\Auth::provider('firebaseuserprovider', function($app, array $config) {
            return new FirebaseUserProvider($app['hash'], $config['model']);
         });
    }
}
