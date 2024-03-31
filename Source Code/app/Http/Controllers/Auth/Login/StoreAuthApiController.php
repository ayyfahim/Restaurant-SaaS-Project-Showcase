<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
class StoreAuthApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        config()->set( 'auth.defaults.guard', 'api' );
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                "success"=> false,
                "status"=>"error",
                "error"=>["code"=>401,
                    "type"=>"Unauthorized",
                    "message"=>"Invalid email or password"
                ],
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        $response = Auth::user();
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'user' => $response,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => '',
            ]
        ], 200);
    }

}
