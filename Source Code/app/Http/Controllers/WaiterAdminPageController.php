<?php

namespace App\Http\Controllers;

use App\Application;
use App\Category;
use App\Kitchen;
use App\Models\Addon;
use App\Models\AddonCategory;
use App\Models\AddonCategoryItem;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\SelectedSubscription;
use App\Models\Setting;
use App\Models\StoreSlider;
use App\Models\StoreSubscription;
use App\Models\Table;
use App\Models\WaiterCall;
use App\Product;
use App\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WaiterAdminPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:waiter');
    }

    public function create_orders(Request $request)
    {
        // dd(session()->get('waiter_cart')["sub_total"]);
        // $request->session()->forget('waiter_cart');
        config(['session.lifetime' => 1440]);
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        // $products = Product::where('store_id', '=', auth()->user()->store_id)
        $products = Product::with('addonItems.categories.addons.nested_addons.addon_category.addons')
        ->where('store_id', '=', auth()->user()->store_id)
            ->where('is_active', '=', 1)->orderBy('index_number')->get();
        $addons = Addon::where('store_id', '=', auth()->user()->store_id)
            ->get();

        return view('waiters.create_orders', [
            'title' => 'Create Orders',
            'root_name' => 'Create Orders',
            'sanboxNumber' => $sanboxNumber,
            'products' => $products,
            'addons' => $addons,
            'tables' => auth()->user()->store->tables,
        ]);
    }

    public function waiter_calls()
    {
        // also table id
        $calls = auth()->user()->waiter_calls();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $call_waiter_count = WaiterCall::all()->where('store_id', '=', auth()->user()->store_id)->count();
        // dd($calls);

        return view('waiters.waiter_calls', [
            'title' => 'Waiter Call',
            'count' => $calls->where('is_completed', '=', 0)->count(),
            'calls' => $calls,
            'root_name' => 'Waiter Call',
            'sanboxNumber' => $sanboxNumber,
            'call_waiter_count' => $call_waiter_count,
        ]);
    }

    public function waiter_shifts()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $call_waiter_count = WaiterCall::all()->where('store_id', '=', auth()->user()->store_id)->count();

        return view('waiters.waiter_shifts', [
            'title' => 'Current Waiter Shifts',
            'root_name' => 'Current Waiter Shifts',
            'sanboxNumber' => $sanboxNumber,
            'call_waiter_count' => $call_waiter_count,
        ]);
    }

    public function order_requests()
    {
        // also table id
        $calls = auth()->user()->order_requests();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $call_waiter_count = WaiterCall::all()->where('store_id', '=', auth()->user()->store_id)->count();

        return view('waiters.order_requests', [
            'title' => 'Order Requests',
            'count' => $calls->where('is_completed', '=', 0)->count(),
            'calls' => $calls,
            'root_name' => 'Order Requests',
            'sanboxNumber' => $sanboxNumber,
            'call_waiter_count' => $call_waiter_count,
        ]);
    }

    public function waiter_calls_old()
    {
        $calls = WaiterCall::all()->where('store_id', '=', auth()->id())->sortByDesc('id');
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

        //        return $customers[0]->total_orders(9544752154);
        return view('restaurants.waiterCall.view', [
            'title' => 'Customers',
            'count' => $calls->where('is_completed', '=', 0)->count(),
            'calls' => $calls,
            'root_name' => 'Waiter Call',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }
}
