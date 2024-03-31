<?php

namespace App\Http\Controllers\Auth\Login;

use App\Application;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Http\Controllers\Auth\LoginController as DefaultLoginController;

class CustomerControllerBackup extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        if ($request->phone_number) {
            $customer = Customer::where('phone', $request->phone_number)->get()->first();

            config()->set('auth.defaults.guard', 'customer');

            if (!$customer) {
                return response()->json([
                    "success" => false,
                    "status" => "error",
                    "message" => "Phone number not registered, please Sign up."
                ], 401);
            }

            if (!$token = auth()->login($customer)) {
                return response()->json([
                    "success" => false,
                    "status" => "error",
                    "message" => "Phone number not registered, please Sign up."
                ], 401);
            }

            return $this->respondWithToken($token);
        } else {
            $credentials = request(['email', 'password']);
            config()->set('auth.defaults.guard', 'customer');
            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    "success" => false,
                    "status" => "error",
                    "message" => "Invalid email or password"
                ], 401);
            }

            return $this->respondWithToken($token);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required_with:email,password|nullable |string|max:255',
            'email' => 'required_with:name,password|nullable |string|email|max:255|unique:customers',
            'password' => 'required_with:email,name|nullable |string|confirmed|min:8|confirmed',
            // 'phoneNumber' => 'required|unique:customers,phone',
            // 'phone' => 'required|unique:customers|phone:' . $request->phoneCountry,
            'phone' => 'required|phone:' . $request->phoneCountry,
            'phoneCountry' => 'required_with:phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => $validator->errors(),
                "message" => "Please fix the below input fields."
            ], 422);
        }

        $phoneNumber = (string) PhoneNumber::make($request->phone, $request->phoneCountry);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $phoneNumber,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // $credentials = [ 'email' => $request->email, 'password' => $request->password];
        config()->set('auth.defaults.guard', 'customer');
        // if (! $token = auth()->attempt($credentials)) {
        //     return response()->json([
        //         "success"=> false,
        //         "status"=>"error",
        //         "message"=> "Invalid email or password"
        //     ], 401);
        // }

        // return $this->respondWithToken($token);
        return response()->json([
            "success" => true,
            "status" => "success",
            "message" => "Registration successfull.",
        ], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:customers,email,' . $request->id,
            'phone' => 'required|unique:customers,phone,' . $request->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => $validator->errors(),
                "message" => "Please fix the below input fields."
            ], 422);
        }

        $customer = Customer::find($request->id);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Invalid customer id."
            ], 401);
        }

        $customer = $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        // $credentials = [ 'email' => $request->email, 'password' => $request->password];
        config()->set('auth.defaults.guard', 'customer');

        return $this->respondWithId($request->id);
    }

    public function updatePassword(Request $request)
    {
        config()->set('auth.defaults.guard', 'customer');

        if ($request->user()->password) {
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
                'new_confirm_password' => ['same:new_password'],
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'current_password' => [],
                'new_password' => ['required'],
                'new_confirm_password' => ['same:new_password'],
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => $validator->errors(),
                "message" => "Please fix the below input fields."
            ], 422);
        }

        config()->set('auth.defaults.guard', 'customer');

        $customer = Customer::find(auth()->user()->id);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Invalid customer id."
            ], 401);
        }

        $customer = $customer->update([
            'password' => Hash::make($request->new_password)
        ]);

        return $this->respondWithId(auth()->user()->id);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        $user = request()->user();
        if (!$user) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Invalid customer id."
            ], 401);
        }

        config()->set('auth.defaults.guard', 'customer');

        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        config()->set('auth.defaults.guard', 'customer');
        auth()->logout();

        return response()->json([
            "success" => true,
            "status" => "success",
        ], 200);
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
        $response = auth()->user();
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'user' => $response,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => '',
            ],
        ], 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithId($id)
    {
        $response = Customer::findOrFail($id);
        $token = auth()->tokenById($id);
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'user' => $response,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => '',
            ],
        ], 200);
    }
}
