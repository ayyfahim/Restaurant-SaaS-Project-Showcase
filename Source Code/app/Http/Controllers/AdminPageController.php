<?php

namespace App\Http\Controllers;

use App\Allergen;
use App\Application;
use App\Homes;
use App\Models\SelectedSubscription;
use App\Models\Setting;
use App\Models\Store;
use App\Models\StoreSubscription;
use App\Payment;
use App\Product;
use App\Slider;
use App\Translation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use function GuzzleHttp\Promise\all;

class AdminPageController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }
    public function dashboard()
    {
        $account_info = Application::all()->first();
        $store_count = Store::all()->count();
        $product_count = Product::all()->count();

        $subscription_earnings = SelectedSubscription::all()->where('payment_status', '=', 'paid')->sum('subscription_price');
        $marketplace_fee_earnings = Payment::all()->where('status', '=', 'Charged')->sum('marketplace_fees');
        $earnings = $subscription_earnings + $marketplace_fee_earnings;

        $new_stores = Store::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $pending_stores = 0;

        $expired_stores = Store::all()->where('subscription_end_date', '<', date('Y-m-d'));


        return view('admin.index', [
            'title' => '',
            'root_name' => 'Dashboard',
            'root' => 'dashboard',
            'account_info' => $account_info,
            'store_count' => $store_count,
            'product_count' => $product_count,
            'pending_stores' => $pending_stores,
            'earnings' => $earnings,
            'new_stores' => $new_stores,
            'expired_stores' => $expired_stores
        ]);
    }

    public function add_store()
    {
        return view('admin.store.add_store', [
            'title' => '',
            'root_name' => 'Store',
            'root' => 'store',
        ]);
    }

    public function all_stores()
    {
        $stores = Store::with('roles')->get();

        return view('admin.store.all_stores', [
            'title' => 'Store',
            'root_name' => 'All Stores',
            'root' => 'All Stores',
            'stores' => $stores
        ]);
    }

    public function view_bank_details(Store $store)
    {
        return view('admin.store.store_bank_details', [
            'title' => 'Store',
            'root_name' => 'Store Bank Detail',
            'root' => 'Store Bank Detail',
            'store' => $store,
            'bank_details' => $store->bank_details,
        ]);
    }

    public function edit_stores(Store $id)
    {

        $head_name = "Update Employee";
        $translations = Translation::all()->where("is_active", "=", 1);

        return view(
            'admin.store.edit_store',
            compact('id', 'translations'),
            [
                'title' => 'Store',
                'root_name' => 'Update Stores',
                'root' => 'store',
            ]
        );
    }

    public function all_slider()
    {
        $sliders = Slider::all()->sortBy('id');
        return view(
            'admin.slider.all_sliders',
            [
                'title' => 'Sliders',
                'root_name' => 'Sliders',
                'root' => 'sliders',
                'sliders' => $sliders
            ]
        );
    }
    public function add_slider()
    {

        return view(
            'admin.slider.add_slider',
            [

                'title' => 'Sliders',
                'root_name' => 'Sliders',
                'root' => 'sliders',

            ]
        );
    }
    public function update_slider(Slider $id)
    {
        return view(
            'admin.slider.update_slider',
            [
                'title' => 'update Sliders',
                'root_name' => 'Sliders',
                'root' => 'sliders',
                'data' => $id

            ]
        );
    }

    public function settings()
    {
        $account_info = Application::all()->first();
        return view(
            'admin.settings.index',
            [
                'title' => 'Settings',
                'root_name' => 'Settings',
                'root' => 'settings',
                'account_info' => $account_info
            ]
        );
    }

    public function paymentsettings()
    {
        $account_info = Application::all()->first();
        $settings = Setting::all();
        return view(
            'admin.settings.paymentsettings',
            [
                'title' => 'Payment Settings',
                'root_name' => 'Settings',
                'root' => 'settings',
                'account_info' => $account_info,
                'settings' => $settings
            ]
        );
    }
    public function account_settings()
    {
        return view(
            'admin.settings.account_settings',
            [
                'root' => 'settings',
                'root_name' => 'Settings',
                'title' => 'Account settings',

            ]
        );
    }

    public function privacy_policy()
    {
        $privacy = Setting::all();
        return view(
            'admin.settings.privacy_policy',
            [
                'root' => 'settings',
                'root_name' => 'Settings',
                'title' => 'Account settings',
                'privacy' => $privacy

            ]
        );
    }

    public function registration_policy()
    {
        $registration_policy = Setting::all();
        return view(
            'admin.settings.registration_policy',
            [
                'root' => 'settings',
                'root_name' => 'Settings',
                'title' => 'Registration Policy',
                'registration_settings' => $registration_policy

            ]
        );
    }

    public function whatsapp()
    {
        $whatsapp = Setting::all();
        return view(
            'admin.settings.whatsapp',
            [
                'root' => 'settings',
                'root_name' => 'Whatsapp Notification',
                'title' => 'Whatsapp Notification',
                'whatsapp' => $whatsapp

            ]
        );
    }


    public function cache_settings()
    {
        return view(
            'admin.settings.cache_settings',
            [
                'root' => 'settings',
                'root_name' => 'Cache',
                'title' => 'Cache',

            ]
        );
    }







    public function subscription()
    {
        $account_info = Application::all()->first();
        $subscription = StoreSubscription::all();

        return view('admin.subscription.all_subscription', [
            'title' => 'Subscription',
            'root_name' => 'Subscription',
            'root' => 'Subscription',
            'account_info' => $account_info,
            'subscription' => $subscription,

        ]);
    }

    public function addsubscription()
    {

        return view('admin.subscription.add_subscription', [
            'title' => 'Subscription',
            'root_name' => 'Subscription',
            'root' => 'Subscription',
        ]);
    }

    public function editsubscription(StoreSubscription $id)
    {
        $head_name = "Update Subscription";

        return view('admin.subscription.edit_subscription', compact('id'), [
            'title' => 'Subscription',
            'root_name' => 'Subscription',
            'root' => 'Subscription',
        ]);
    }


    public function tables()
    {

        return view('admin.tables.all_tables', [
            'title' => 'Tables',
            'root_name' => 'Tables',
            'root' => 'Tables',
        ]);
    }
    public function translations()
    {
        $data = Translation::all();
        return view('admin.translations.index', [
            'title' => 'Translations',
            'root_name' => 'Translations',
            'root' => 'translations',
            'data' => $data
        ]);
    }
    public function add_translations()
    {

        return view('admin.translations.add_translation', [
            'title' => 'Translation',
            'root_name' => 'Translation',
            'root' => 'translation',
        ]);
    }
    public function update_translation(Translation $id)
    {

        return view('admin.translations.update_translation', [
            'title' => 'Translation',
            'root_name' => 'Translation',
            'root' => 'translation',
            'data' => $id
        ]);
    }

    public function all_allergens()
    {


        $allergens = Allergen::all();

        return view('admin.allergens.all_allergens', [
            'title' => 'Allergens',
            'root_name' => 'All Allergens',
            'root' => 'Allergens',
            'allergens' => $allergens
        ]);
    }

    public function add_allergens()
    {
        return view('admin.allergens.add_allergens', [
            'title' => 'Allergens',
            'root_name' => 'Add Allergens',
            'root' => 'Allergens',
        ]);
    }

    public function edit_allergen(Allergen $allergen)
    {
        return view('admin.allergens.edit_allergens', [
            'title' => 'Allergens',
            'root_name' => 'Edit Allergens',
            'root' => 'Allergens',
            'allergen' => $allergen,
        ]);
    }
}
