<?php

namespace App\Http\Controllers\Auth\Login;

use App\Allergen;
use Seshac\Otp\Otp;
use App\Application;
use App\Card;
use App\Models\Customer;
use App\Mail\SendOtpToMail;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Providers\RouteServiceProvider;
use Kreait\Firebase\Request\CreateUser;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth as FirebaseAuth;
use Propaganistas\LaravelPhone\PhoneNumber;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Exception\FirebaseException;
use App\Http\Controllers\Auth\LoginController as DefaultLoginController;

class CustomerController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->middleware('auth:customer', ['only' => ['logout', 'update', 'updatePassword', 'me', 'guard']]);
        $this->auth = $auth;
        JWTAuth::factory()->setTTL(config('jwt.ttl'));
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function user(Request $request){
        return $request->user();
    }

    public function login(Request $request)
    {
        try {
            $user = $this->auth->getUserByPhoneNumber($request->phone_number);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Please try again!"
            ], 401);
        }

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
    }

    public function checkCustomerByPhone(Request $request)
    {
        $data = json_decode($request->getContent());
        $phone_number = $data->phone_number;
        try {
            $user = $this->auth->getUserByPhoneNumber($phone_number);

            $customer = Customer::where('phone', $phone_number)->get()->first();

            if (!$customer) {
                throw new \Exception("Customer dont exist", 401);
            }

            return response()->json([
                "success" => true,
                "status" => "success",
                "first_name" => $customer->first_name
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Phone number not registered, please Sign up.",
            ], 401);
        }
    }

    public function handleCallback(Request $request, $provider) {
        $socialTokenId = $request->input('socialLoginTokenId', '');

        try {
           $verifiedIdToken = $this->auth->verifyIdToken($socialTokenId);

           $customer = Customer::where('email', $verifiedIdToken->getClaim('email'))->get()->first();

            if (!$customer) {
                $customer = Customer::create([
                    'email' => $verifiedIdToken->getClaim('email'),
                ]);
            }

            config()->set('auth.defaults.guard', 'customer');
            $token = auth()->login($customer);

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'user' => auth()->user()->load('allergens', 'cards'),
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => '',
                ],
            ], 200);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Invalid email or password"
            ], 401);
        } catch (InvalidToken $e) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Invalid email or password"
            ], 401);
        }
     }

    public function registerFirebase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone:' . $request->phoneCountry,
            'phoneCountry' => 'required_with:phone',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $phoneNumber = (string) PhoneNumber::make($request->phone, $request->phoneCountry);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => $validator->errors(),
                "message" => "Please fix the below input fields."
            ], 422);
        }

        if (Customer::wherePhone($phoneNumber)->first()) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => [
                    "phone" => [
                        ["This phone already exist"]
                    ]
                ],
                "message" => "This phone already exist."
            ], 422);
        }

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $phoneNumber,
        ]);

        $userProperties = [
            // 'email' => $request->input('email'),
            // 'emailVerified' => false,
            'password' => 'password',
            'displayName' => $customer->full_name,
            'disabled' => false,
        ];

        try {
            // $createdUser = $this->auth->createUser($userProperties);

            $request = CreateUser::new()
                ->withPhoneNumber($phoneNumber)
                ->withClearTextPassword('password')
                ->withDisplayName($customer->full_name);

            $this->auth->createUser($request);


            // $sendEmail = $this->auth->sendEmailVerificationLink( $request->input('email'));
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => [],
                "message" => $th->getMessage()
            ], 422);
        }

        config()->set('auth.defaults.guard', 'customer');
        $token = auth()->login($customer);
        $response = $this->guard()->user();
        $allergens = [];
        $get_allergens = Allergen::all();
        foreach ($get_allergens as $value) {
            $allergens[] = $value;
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'user' => $response->load('allergens', 'cards'),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'allergens' => $allergens,
            ],
        ], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|nullable|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:customers,email,' . $request->id,
            'phone' => 'sometimes|required|unique:customers,phone,' . $request->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => $validator->errors(),
                "message" => "Please fix the below input fields."
            ], 422);
        }

        $customer = Customer::find($request->user()->id);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Invalid customer id."
            ], 401);
        }

        try {
            DB::beginTransaction();
            $customer->update($validator->valid());
            $customer->refresh();

            if ($customer->phone) {
               $firebaseUser = $this->auth->getUserByPhoneNumber($customer->phone);
            } else if ($customer->email) {
                $firebaseUser = $this->auth->getUserByEmail($customer->email);
            }

            $uid = $firebaseUser->uid;

            $properties = [
                'password' => 'password',
            ];

            if ($customer->full_name) $properties['displayName'] = $customer->full_name;
            if ($customer->email) $properties['email'] = $customer->email;
            if ($customer->phone) $properties['phoneNumber'] = $customer->phone;

            $updatedUser = $this->auth->updateUser($uid, $properties);

        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error($th->getMessage(), ['customer' => $customer]);

            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Sorry can't update your profile.",
                "messageAgain" => $th->getMessage(),
            ], 401);
        }

        DB::commit();

        config()->set('auth.defaults.guard', 'customer');

        return $this->respondWithId($request->user()->id, "Profile Updated Successfully");
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

    public function deleteCard(Request $request)
    {
        config()->set('auth.defaults.guard', 'customer');

        // dd(json_decode($request->getContent())->id);

        Card::where([
            'customer_id' => $request->user()->id,
            'id' => json_decode($request->getContent())->id
        ])->get()->first()->delete();

        return $this->respondWithId(auth()->user()->id);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(Request $request)
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

        return $this->respondWithToken($request->bearerToken());
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('customer');
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

    public function sendOtpUsingFirebase(Request $request)
    {
        $actionCodeSettings = [
            'continueUrl' => config('app.url') . '/account/login',
        ];

        try {
            $this->auth->sendSignInWithEmailLink($request->email, $actionCodeSettings);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Can't send OTP to email.."
            ], 401);
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "message" => "OTP sent to email successfully.",
        ], 200);
    }

    public function verifyOtpEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'string|email|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "status" => "error",
                'errors' => $validator->errors(),
                "message" => "Please fix the below input fields."
            ], 422);
        }

        $verify = Otp::validate($request->email, $request->code);

        if ($verify->status !== true) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "Can't verify code."
            ], 401);
        }

        $customer = Customer::where('email', $request->email)->get()->first();
        if (!$customer) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "message" => "User not found"
            ], 401);
        }

        config()->set('auth.defaults.guard', 'customer');
        $token = auth()->login($customer);

        return $this->respondWithToken($token);
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
        $response = $this->guard()->user();
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'user' => $response->load('allergens', 'cards'),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
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
    protected function respondWithId($id, $message = null)
    {
        $response = Customer::findOrFail($id);
        $token = auth()->tokenById($id);

        return response()->json([
            "success" => true,
            "status" => "success",
            "message" => $message,
            "payload" => [
                'user' => $response->load('allergens', 'cards'),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => '',
            ],
        ], 200);
    }
}
