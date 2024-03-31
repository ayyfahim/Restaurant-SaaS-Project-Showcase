<?php

namespace App\Http\Controllers\Home;

use App\Homes;
use App\Slider;
use App\Product;
use App\Category;
use Carbon\Carbon;
use App\Application;
use App\Models\Store;
use App\Models\Setting;
use Cmixin\BusinessTime;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StoreSubscription;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TranslationService;
use Hashids\Hashids;
use App\Mail\SendVerificationMail;
use App\Mail\SendStoreDocuments;
use Crypt;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as Download;

class StoreHomeController extends Controller
{
    public function storeGetStarted()
    {
        // $transation = new TranslationService();
        // $account_info = Application::all()->first();

        // return view('Home.get_started', [
        //     'account_info' => $account_info,
        //     'languages' => $transation->languages(),
        //     'selected_language' => $transation->selected_language()
        // ]);

        return view('Home.show_store');
    }

    public function home()
    {
        $transation = new TranslationService();

        $account_info = Application::all()->first();
        return view('Home.index', [
            'account_info' => $account_info,
            'languages' => $transation->languages(),
            'selected_language' => $transation->selected_language()
        ]);
    }

    public function allLoginMethods()
    {
        $account_info = Application::all()->first();
        return view('auth.login.all_login_methods')->with(['account_info' => $account_info]);
    }


    public function start()
    {
        $account_info = Application::all()->first();
        return view('Home.start', [
            'account_info' => $account_info
        ]);
    }

    public function downloadFile()
    {
        $fileName = Setting::all()->where('key', 'SignupTermFile')->first()->value;
        $headers = [
            'Content-Type'        => 'Content-Type: application/zip',
            'Content-Disposition' => 'attachment; filename="' . $fileName  . '"',
        ];
        $filetopath = Storage::disk('s3')->get("settings/$fileName");
        return Download::make($filetopath, 200, $headers);
    }

    public function register()
    {
        $subscription = StoreSubscription::all();
        $signup_term = Setting::all()->where('key', 'SignupTermText')->first()->value;
        $account_info = Application::all()->first();
        return view('Home.register', [
            'account_info' => $account_info,
            'subscription' => $subscription,
            'signup_term' => $signup_term
        ]);
    }

    public function pricing()
    {
        $subscription = StoreSubscription::all();
        $account_info = Application::all()->first();
        return view('Home.pricing', [
            'account_info' => $account_info,
            'subscription' => $subscription
        ]);
    }

    public function privacy()
    {
        $privacy = Setting::all()->where('key', 'PrivacyPolicy')->first()->value;
        $home = Homes::all();
        $account_info = Application::all()->first();
        return view('Home.privacy', [
            'account_info' => $account_info,
            'home' => $home,
            'privacy' => $privacy
        ]);
    }


    public function index($view_id)
    {
        //        return 1;
        $account_info = Application::all()->first();

        if (Store::all()->where('view_id', '=', $view_id)->count() == 0) {
            abort(404);
        }
        if (Store::all()->where('view_id', '=', $view_id)->where('is_visible', '=', 1)->where('subscription_end_date', '>=', date('Y-m-d'))->where('is_accept_order', '=', 1)->count() == 0) {
            return view('Home.404', [
                'account_info' => $account_info,
            ]);
        }

        $store_open_hour = Store::all()->where('view_id', '=', $view_id)->first()->open_hours->data;

        // dd(Carbon::parse($store_open_hour['monday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['monday']['end_time'])->format('H:i'));


        // As a second argument you can set default opening hours:
        BusinessTime::enable(Carbon::class, [
            'monday' => [Carbon::parse($store_open_hour['monday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['monday']['end_time'])->format('H:i')],
            'tuesday' => [Carbon::parse($store_open_hour['tuesday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['tuesday']['end_time'])->format('H:i')],
            'wednesday' => [Carbon::parse($store_open_hour['wednesday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['wednesday']['end_time'])->format('H:i')],
            'thursday' => [Carbon::parse($store_open_hour['thursday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['thursday']['end_time'])->format('H:i')],
            'friday' => [Carbon::parse($store_open_hour['friday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['friday']['end_time'])->format('H:i')],
            'saturday' => [Carbon::parse($store_open_hour['saturday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['saturday']['end_time'])->format('H:i')],
            'sunday' => [Carbon::parse($store_open_hour['sunday']['start_time'])->format('H:i') . "-" . Carbon::parse($store_open_hour['sunday']['end_time'])->format('H:i')],
            // 'exceptions' => [
            //     '2016-11-11' => ['09:00-12:00'],
            //     '2016-12-25' => [],
            //     '01-01' => [], // Recurring on each 1st of january
            //     '12-25' => ['09:00-12:00'], // Recurring on each 25th of december
            // ],
            // You can use the holidays provided by BusinessDay
            // and mark them as fully closed days using 'holidaysAreClosed'
            // 'holidaysAreClosed' => true,
            // Note that exceptions will still have the precedence over
            // the holidaysAreClosed option.
            // 'holidays' => [
            //     'region' => 'us-ny', // Load the official list of holidays from USA - New York
            //     'with' => [
            //         'labor-day' => null, // Remove the Labor Day (so the business is open)
            //         'company-special-holiday' => '04-07', // Add some custom holiday of your company
            //     ],
            // ],
        ]);

        // if (!Carbon::now()->isOpen()) {
        //     return view('Home.404', [
        //         'account_info' => $account_info,
        //     ]);
        // }

        $store = Store::all()->where('view_id', '=', $view_id)->first();
        $store_id  = $store['id'];
        $store_name  = $store['store_name'];
        $description  = $store['description'];

        return view('Home.show_store', [
            'account_info' => $account_info,
        ]);
    }

    public function isNowBetweenTimes($timezone, $startDateTime, $endDateTime)
    {
        $curTimeLocal = Carbon::now($timezone);
        $startTime = $curTimeLocal->copy();
        $startTime->hour = $startDateTime->hour;
        $startTime->minute = $startDateTime->minute;
        $endTime = $curTimeLocal->copy();
        $endTime->hour = $endDateTime->hour;
        $endTime->minute = $endDateTime->minute;
        if ($endTime->lessThan($startTime)) {
            $endTime->addDay();
        }

        return ($curTimeLocal->isBetween($startTime, $endTime));
    }


    public function indexjs($view_id)
    {
        if (Store::all()->where('view_id', '=', $view_id)->count() == 0) {
            abort(404);
        }
        if (Store::all()->where('view_id', '=', $view_id)->where('is_visible', '=', 1)->count() == 0) {
            return view('Home.404');
        }

        $store = Store::all()->where('view_id', '=', $view_id)->first();
        $store_id  = $store['id'];
        $store_name  = $store['store_name'];
        $description  = $store['description'];
        $sliders = Slider::all()->where('is_visible', '=', 1);
        $recommended = Product::all()->where('store_id', '=', $store_id)
            ->where('is_recommended', '=', 1)
            ->where('is_active', '=', 1)->sortBy('name');

        $categories = Category::all()->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1)->sortBy('name');

        $products = Product::all()->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1)->sortBy('name');
        $account_info = Application::all()->first();

        return view('Home.show_store_old', [
            'recommended' => $recommended,
            'categories' => $categories,
            'products' => $products,
            'account_info' => $account_info,
            'store_name' => $store_name,
            'description' => $description,
            'sliders' => $sliders
        ]);
    }

    public function RegisterNewStore(Request $request)
    {
        $data = request()->validate([
            'store_name' => 'required',
            'email' => ['required', Rule::unique('stores', 'email')],
            'password' => 'required',
            'phone' => 'required',
            'address' => '',
            'description' => '',
            'theme_id' => '',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['logo_url'] = null;
        $data['view_id'] = sha1(time());
        $data['is_visible'] = 0;
        // $plan = StoreSubscription::all()->where('id', '=', $request->plan)->first();

        // if ($plan->price == 0) {
        //     $data['subscription_end_date'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + ' . $plan->days . ' days'));
        // } else {
        //     $data['subscription_end_date'] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 days'));
        // }

        $data['subscription_end_date'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + ' . 368 . ' days'));

        if ($store = Store::create($data)) {

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $d = new \DateTime();
                    $d = $d->format("YmdHisu");
                    $storeUploads = $store->id . '_' . $d . '.' . $file->getClientOriginalExtension();
                    Storage::disk('s3')->putFileAs("/restaurant-uploads/$store->id/", $file, $storeUploads, 'public');
                }
            }
            if ($data) {
                $role = Role::where('name', 'owner')->first();
                $store->assignRole($role);
                $rolePermissions = DB::table("roles")->where("roles.id", $role->id)
                    ->join("role_has_permissions", "roles.id", "role_has_permissions.role_id")
                    ->pluck('role_has_permissions.permission_id')
                    ->all();
                $store->syncPermissions($rolePermissions);
            }
            $token = Crypt::encrypt($data['email']);
            $link = config('app.url') . "/store/verify/" . $token;
            try {
                Mail::to($data['email'])->send(new SendVerificationMail($link));
            } catch (\Throwable $th) {
                return redirect()->back()->with("MSG", $th->getMessage())->with("TYPE", "danger");
            }
            // Mail::to($data['email'])->send(new SendVerificationMail($link));
            return redirect(route('store.login'))->with("MSG", "We have sent you email notification.")->with("TYPE", "success");
        }
    }

    public function verifyMail(Request $request, $token)
    {
        $email = Crypt::decrypt($token);
        $store = Store::where("email", $email)->first();
        if (!$store) {
            return redirect(route('store.login'))->with("MSG", "Invalid Link.")->with("TYPE", "error");
        }
        if ($store->is_visible == 1) {
            return redirect(route('store.login'))->with("MSG", "Account already verified.")->with("TYPE", "success");
        }
        $store->is_visible = 1;
        $store->save();
        $files = Storage::disk('s3')->files("restaurant-uploads/$store->id/");

        $verification_email = Setting::all()->where('key', 'DocVerificationEmail')->first()->value;
        if ($verification_email) {
            Mail::to($verification_email)->send(new SendStoreDocuments($store, $files));
        }
        return redirect(route('store.login'))->with("MSG", "Your account has been activated.")->with("TYPE", "success");
    }

    public function product_details()
    {
        //        return 1;

        return view('Home.show_store');
    }
}
