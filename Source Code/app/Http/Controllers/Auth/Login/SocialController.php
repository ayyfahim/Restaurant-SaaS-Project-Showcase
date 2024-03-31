<?php

namespace App\Http\Controllers\Auth\Login;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

use Kreait\Firebase\Auth as FirebaseAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
        JWTAuth::factory()->setTTL(config('jwt.ttl'));
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(string $provider)
    {

        // this user we get back is not our user model, but a special user object that has all the information we need
        $providerUser = Socialite::driver($provider)->stateless()->user();

        // we have successfully authenticated via facebook at this point and can use the provider user to log us in.

        // for example we might do something like... Check if a user exists with the email and if so, log them in.
        $customer = Customer::where('email', $providerUser->getEmail())->get()->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
            ]);
        }

        config()->set('auth.defaults.guard', 'customer');
        $token = auth()->login($customer);

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'user' => auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => '',
                '$providerUser' => $providerUser,
            ],
        ], 200);
    }
}
