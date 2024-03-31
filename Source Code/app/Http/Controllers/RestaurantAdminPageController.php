<?php

namespace App\Http\Controllers;

use App\Allergen;
use App\Application;
use App\BankDetail;
use App\Category;
use App\Coupon;
use App\Discount;
use App\Kitchen;
use App\Menu;
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
use App\TimeRestriction;
use App\Waiter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RestaurantAdminPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:store');
        // $this->middleware('auth:waiter')->only('new_orders', 'new_waiter_calls');
    }
    public function index()
    {
        $store_id = Auth::user()->id;
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $IsStoreEnabled = Setting::all()->where('key', 'IsStoreEnabled')->first()->value;
        $order_count = Order::all()->where('store_id', '=', $store_id)->count();
        $call_waiter_count = WaiterCall::all()->where('store_id', '=', $store_id)->count();
        $item_sold = DB::table('orders')->where('store_id', '=', $store_id)
            ->select('*')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', '=', 4)
            ->get()->sum('quantity');

        // $earnings = Order::all()->where('status', '=', 4)->where('store_id', '=', $store_id)->sum('total');
        $earnings = Order::all()->where('is_paid', '=', 1)->where('store_id', '=', $store_id)->sum('total');
        $account_info = Application::all()->first();
        $orders = Order::all()->SortByDesc('id')->where('store_id', auth()->id())->where('status', '=', 1);


        $notification = $this->notification();



        return view('restaurants.dashboard', [
            "order_count" => $order_count,
            'call_waiter_count' => $call_waiter_count,
            "item_sold" => $item_sold,
            "earnings" => $earnings,
            "account_info" =>  $account_info,
            'orders' => $orders,
            'notification' => $notification,
            'sanboxNumber' => $sanboxNumber,
            'IsStoreEnabled' => $IsStoreEnabled,
            'root_name' => 'Dashboard',
            'bank_detail' => Auth::user()->bank_details->last(),
        ]);
    }
    public function orderstatus()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $orders = Order::all()->SortByDesc('id')->where('store_id', auth()->id())->where('status', '=', 2);
        $neworder = Order::all()->SortByDesc('id')->where('store_id', auth()->id())->where('status', '=', 1);
        $ready = Order::all()->SortByDesc('id')->where('store_id', auth()->id())->where('status', '=', 5);
        return view('restaurants.orderstatus', [
            'orders' => $orders,
            'neworder' => $neworder,
            'ready' => $ready,
            'root_name' => 'Order Status Screen',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function orders()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

        $orders = Order::all()->SortByDesc('id')->where('store_id', auth()->id());
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->id())->count();
        return view('restaurants.orders', [
            'orders' => $orders,
            'orders_count' => $orders_count,
            'root_name' => 'Orders',
            'sanboxNumber' => $sanboxNumber,
            'notification' => $this->notification(),
            "order_count" => Order::all()->where('store_id', '=', auth()->id())->count(),
            'call_waiter_count' => WaiterCall::all()->where('store_id', '=', auth()->id())->count(),
        ]);
    }
    public function new_orders()
    {


        $orders = Order::all()->SortByDesc('id')->where('store_id', auth()->id());
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->id())->count();
        $response = array();
        foreach ($orders as $data)
            $response[] = $data;

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'orders' => $response,
                'count' => $orders_count
            ]
        ], 200);
    }

    public function new_waiter_calls()
    {
        $orders = WaiterCall::all()->where('store_id', auth()->id());
        $orders_count = WaiterCall::all()->SortByDesc('id')->where('store_id', auth()->id())->count();
        $response = array();
        foreach ($orders as $data)
            $response[] = $data;

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'waiter_calls' => $response,
                'call_waiter_count' => $orders_count
            ]
        ], 200);
    }
    public function view_order(Order $id)
    {

        $orderDetails =  Order::with('orderDetails.OrderDetailsExtraAddon')->where('id', $id->id)->get()->toArray();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        //        return OrderDetails::with('OrderDetailsExtraAddon')->get();
        //        return $orderDetails;
        $account_info = Application::all()->first();
        return view('restaurants.view_order', [
            'order' => $id->load('waiter_orders'),
            'orderDetails' => $orderDetails,
            'account_info' => $account_info,
            'root_name' => 'Orders',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function categories()
    {

        $category_count = Category::all()->where('store_id', auth()->id())->count();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

        $category = Category::all()->SortByDesc('id')->where('store_id', auth()->id());
        return view('restaurants.category', [
            'title' => 'category',
            'root_name' => 'category',
            'root' => 'category',
            'category' => $category,
            'category_count' => $category_count,
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function addcategories()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());
        $menus = Menu::all()->where('store_id', auth()->id());
        return view('restaurants.addcategory',
        ['root_name' => 'Category', 'sanboxNumber' => $sanboxNumber, 'kitchen_locations' => $kitchen_locations, 'menus' => $menus]
        );
    }
    public function update_category(Category $id)
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());
        $menus = Menu::all()->where('store_id', auth()->id());

        return view(
            'restaurants.editcategory',
            [
                'title' => 'update Category',
                'root_name' => 'Category',
                'root' => 'Category',
                'data' => $id,
                'sanboxNumber' => $sanboxNumber,
                'kitchen_locations' => $kitchen_locations,
                'menus' => $menus

            ]
        );
    }

    public function products()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $products_count = Product::all()->where('store_id', auth()->id())->count();
        $products = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('type', 1);
        $productsnonveg = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('is_veg', '=', 0)->where('type', 1);
        $productsveg = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('is_veg', '=', 1)->where('type', 1);
        $productsdisabled = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('is_active', '=', 0)->where('type', 1);
        return view('restaurants.products', [
            'products' => $products,
            'products_count' => $products_count,
            'root_name' => 'Products',
            'productsnonveg' => $productsnonveg,
            'productsveg' => $productsveg,
            'productsdisabled' => $productsdisabled,
            'sanboxNumber' => $sanboxNumber,

        ]);
    }

    public function setmenus()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $products_count = Product::all()->where('store_id', auth()->id())->count();
        $products = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('type', 2);
        $productsnonveg = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('is_veg', '=', 0)->where('type', 2);
        $productsveg = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('is_veg', '=', 1)->where('type', 2);
        $productsdisabled = Product::all()->SortByDesc('id')->where('store_id', auth()->id())->where('is_active', '=', 0)->where('type', 2);
        return view('restaurants.setmenus', [
            'products' => $products,
            'products_count' => $products_count,
            'root_name' => 'Products',
            'productsnonveg' => $productsnonveg,
            'productsveg' => $productsveg,
            'productsdisabled' => $productsdisabled,
            'sanboxNumber' => $sanboxNumber,

        ]);
    }

    public function addproducts()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $category = Category::all()->where('store_id', auth()->id());
        $addon_category = AddonCategory::all()->where('store_id', auth()->id());
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());
        $time_restrictions = TimeRestriction::all()->where('store_id', auth()->id());
        $allergens = Allergen::all()->where('type', 1);
        $food_preferences = Allergen::all()->where('type', 2);

        return view('restaurants.addproducts', [
            'category' => $category,
            'addon_category' => $addon_category,
            'kitchen_locations' => $kitchen_locations,
            'root_name' => 'Products',
            'sanboxNumber' => $sanboxNumber,
            'time_restrictions' => $time_restrictions,
            'allergens' => $allergens,
            'food_preferences' => $food_preferences,
        ]);
    }

    public function addsetmenu()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        // $category = Category::all()->where('store_id', auth()->id());
        $addon_category = AddonCategory::all()->where('store_id', auth()->id());
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());
        $time_restrictions = TimeRestriction::all()->where('store_id', auth()->id());
        $allergens = Allergen::all()->where('type', 1);
        $food_preferences = Allergen::all()->where('type', 2);

        return view('restaurants.addsetmenu', [
            // 'category' => $category,
            'addon_category' => $addon_category,
            'kitchen_locations' => $kitchen_locations,
            'root_name' => 'Set Menu',
            'sanboxNumber' => $sanboxNumber,
            'time_restrictions' => $time_restrictions,
            'allergens' => $allergens,
            'food_preferences' => $food_preferences,
        ]);
    }

    public function addkitchenlocation()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.addkitchenlocation', [
            'root_name' => 'Add Kitchen Location',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function update_products(Product $id)
    {
        if ($id->type==2) {
            abort(404);
        }

        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $addon_category = AddonCategory::all()->where('store_id', auth()->id());
        $category = Category::all()->where('store_id', auth()->id());
        $addon_category_items = AddonCategoryItem::all()->where('product_id', '=', $id->id);
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());
        $time_restrictions = TimeRestriction::all()->where('store_id', auth()->id());
        $id->load('time_restrictions', 'allergens');
        $allergens = Allergen::all()->where('type', 1);
        $food_preferences = Allergen::all()->where('type', 2);

        // dd($id);

        return view(
            'restaurants.editproducts',
            [
                'title' => 'update Products',
                'root_name' => 'Products',
                'root' => 'Products',
                'data' => $id,
                'category' => $category,
                'addon_category' => $addon_category,
                'sanboxNumber' => $sanboxNumber,
                'kitchen_locations' => $kitchen_locations,
                'addon_category_items' => $addon_category_items,
                'time_restrictions' => $time_restrictions,
                'allergens' => $allergens,
                'food_preferences' => $food_preferences,
            ]
        );
    }

    public function update_setmenu(Product $id)
    {
        if ($id->type==1) {
            abort(404);
        }

        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $addon_category = AddonCategory::all()->where('store_id', auth()->id());
        // $category = Category::all()->where('store_id', auth()->id());
        $addon_category_items = AddonCategoryItem::all()->where('product_id', '=', $id->id);
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());
        $time_restrictions = TimeRestriction::all()->where('store_id', auth()->id());
        $id->load('time_restrictions', 'allergens');
        $allergens = Allergen::all()->where('type', 1);
        $food_preferences = Allergen::all()->where('type', 2);

        // dd($id);

        return view(
            'restaurants.editsetmenu',
            [
                'title' => 'update set menu',
                'root_name' => 'Set Menu',
                'root' => 'Set Menu',
                'data' => $id,
                // 'category' => $category,
                'addon_category' => $addon_category,
                'sanboxNumber' => $sanboxNumber,
                'kitchen_locations' => $kitchen_locations,
                'addon_category_items' => $addon_category_items,
                'time_restrictions' => $time_restrictions,
                'allergens' => $allergens,
                'food_preferences' => $food_preferences,
            ]
        );
    }


    public function addtimerestrictions()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $time_restrictions_count = TimeRestriction::all()->where('store_id', auth()->id())->count();
        $time_restrictions = TimeRestriction::all()->SortByDesc('id')->where('store_id', auth()->id());
        return view('restaurants.time_restrictions.time_restrictions', [
            'time_restrictions' => $time_restrictions,
            'time_restrictions_count' => $time_restrictions_count,
            'root_name' => 'Addon Category',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function edittimerestrictions(Request $request, $id)
    {
        // dd(Carbon::now()->format('H:i'));
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $time_restriction = TimeRestriction::findOrFail($id);
        return view('restaurants.time_restrictions.edit_time_restrictions', [
            'time_restriction' => $time_restriction,
            'root_name' => 'Addon Category',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function addon_categories()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $addons_count = AddonCategory::all()->where('store_id', auth()->id())->count();
        $addons = AddonCategory::all()->SortByDesc('id')->where('store_id', auth()->id());
        return view('restaurants.addons.addon_categories', [
            'addons' => $addons,
            'addons_count' => $addons_count,
            'root_name' => 'Addon Category',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function addon_categories_edit(AddonCategory $id)
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $addon_count = Addon::all()->where('store_id', auth()->id())->count();
        $addons_category = AddonCategory::all()->where('store_id', auth()->id());
        $addons_categories = AddonCategory::where('store_id', auth()->id())
                                ->where('id', '!=', $id->id)
                                ->get();
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());

        return view(
            'restaurants.addons.edit_addon_categories',
            [
                'title' => 'update Category',
                'root_name' => 'Category',
                'root' => 'Category',
                'data' => $id->load('addons'),
                'root_name' => 'Addon Category',
                'sanboxNumber' => $sanboxNumber,
                'addon_count' => $addon_count,
                'addons_category' => $addons_category,
                'addons_categories' => $addons_categories,
                'kitchen_locations' => $kitchen_locations,

            ]
        );
    }


    public function addon()
    {
        $addons_category = AddonCategory::all()->where('store_id', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $addon_count = Addon::all()->where('store_id', auth()->id())->count();
        $addon = Addon::all()->SortByDesc('id')->where('store_id', auth()->id());
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());
        return view('restaurants.addons.addon', [
            'addon' => $addon,
            'addon_count' => $addon_count,
            'addons_category' => $addons_category,
            'kitchen_locations' => $kitchen_locations,
            'root_name' => 'Addons',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function update_addon(Addon $id)
    {

        $addons_category = AddonCategory::all()->where('store_id', auth()->id());
        $addon_count = Addon::all()->where('store_id', auth()->id())->count();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $kitchen_locations = Kitchen::all()->where('store_id', auth()->id());

        // dd($id->nested_addons->pluck('id'));

        return view('restaurants.addons.update_addon', [
            'addon' => $id,
            'addon_count' => $addon_count,
            'kitchen_locations' => $kitchen_locations,
            'addons_category' => $addons_category,
            'root_name' => 'Addons',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function menues()
    {
        $menu_count = Menu::all()->where('store_id', auth()->id())->count();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

        $menues = Menu::all()->SortByDesc('id')->where('store_id', auth()->id());

        return view('restaurants.menues', [
            'title' => 'Menues',
            'root_name' => 'Menues',
            'root' => 'Menues',
            'menues' => $menues,
            'menu_count' => $menu_count,
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function update_menues(Menu $id)
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

        return view('restaurants.editmenu', [
            'menu' => $id,
            'root_name' => 'Edit Menu',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function tables()
    {

        $tables = Table::with('waiters')->get()->SortByDesc('id')->where('store_id', auth()->id());
        $waiters = Waiter::all()->SortByDesc('id')->where('store_id', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.tables.all_tables', [
            'title' => 'All Tables',
            'tables' => $tables,
            'waiters' => $waiters,
            'root_name' => 'Tables',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }
    public function table_report()
    {
        $tables = Table::all()->SortByDesc('id')->where('store_id', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.tables.table_report', [
            'title' => 'All Tables',
            'tables' => $tables,
            'root_name' => 'Table Report',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }
    public function add_table()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;


        return view('restaurants.tables.add_new_table', [
            'title' => 'Add New Tables',
            'root_name' => 'Tables',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }


    public function edit_table(Table $id)
    {
        $head_name = "Update Table";
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.tables.edit_table', compact('id'), [
            'title' => 'Table',
            'root_name' => 'Table',
            'root' => 'Table',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }


    public function banner()
    {
        $banner = StoreSlider::all()->SortByDesc('id')->where('store_id', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.banner.banner', [
            'title' => 'All Tables',
            'banner' => $banner,
            'root_name' => 'Discounts and Banners',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function banneredit(StoreSlider $id)
    {
        $head_name = "Update Banner";
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.banner.edit_banner', compact('id'), [
            'title' => 'Banner',
            'root_name' => 'Banner',
            'root' => 'Banner',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }




    public function addbanner()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.banner.addbanner', [
            'title' => 'Add Banner',
            'root_name' => 'Banners',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function subscription_plans()
    {
        $publishableKey = Setting::all()->where('key', '=', 'StripePublishableKey')->first()->value;
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $subscription = StoreSubscription::all()->where('is_active', '=', 1)->where('price', '!=', 0);
        $subscription_count = StoreSubscription::all()->where('is_active', '=', 1)->where('price', '!=', 0)->count();
        $isStripeEnabled =  Setting::all()->where('key', '=', 'IsStripePaymentEnabled')->first()->value;

        $razorpay_key_id = Setting::all()->where('key', '=', 'RazorpayKeyId')->first()->value;
        $razorpayEnabled =  Setting::all()->where('key', '=', 'IsRazorpayPaymentEnabled')->first()->value;

        $currency = Setting::all()->where('key', '=', 'Currency')->first()->value;
        $logo = Application::first()->application_logo;

        return view('restaurants.plans', [
            'title' => 'Subscription Plans',
            'subscription_count' => $subscription_count,
            'subscription' => $subscription,
            'publishableKey' => $publishableKey,
            'isStripeEnabled' => $isStripeEnabled,
            'root_name' => 'Subscription',
            'sanboxNumber' => $sanboxNumber,
            'razorpayEnabled' =>  $razorpayEnabled,
            'razorpay_key_id' => $razorpay_key_id,
            'currency' => $currency,
            'logo' => $logo

        ]);
    }
    public function subscription_history()
    {
        $store_plan_history = SelectedSubscription::all()->where('store_id', '=', \auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.store_subscription.history', [
            'store_plan_history' => $store_plan_history,
            'root_name' => 'Subscription History',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function add_deliverect()
    {
        $store = Auth::user();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

        return view('restaurants.settings.add_deliverect', [
            'title' => 'Deliverect',
            'store' => $store,
            'root_name' => 'Deliverect',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function add_store_location()
    {
        $store = Auth::user();

        return view('restaurants.settings.add_store_location', [
            'title' => 'Add Store Location',
            'store' => $store,
            'root_name' => 'Add Store Location',
        ]);
    }

    public function add_bank_details()
    {
        $store = Auth::user();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $bankDetail = Auth::user()->bank_details->last();

        return view('restaurants.settings.add_bank_details', [
            'title' => 'Add Bank Details',
            'store' => $store,
            'root_name' => 'Add Bank Details',
            'sanboxNumber' => $sanboxNumber,
            'bankDetail' => $bankDetail,
        ]);
    }

    public function add_open_hours()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $open_hours = Auth::user()->open_hours ? Auth::user()->open_hours->data : [];

        return view('restaurants.settings.add_open_hours', [
            'title' => 'Add Open Hour',
            'store' => Auth::user(),
            'root_name' => 'Add Open Hour',
            'sanboxNumber' => $sanboxNumber,
            'open_hours' => $open_hours,
        ]);
    }

    public function settings()
    {
        $store = Auth::user();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        // $bankDetail = BankDetail::where('store_id', \auth()->id())->get()->first();
        $bankDetail = Auth::user()->bank_details->last();

        return view('restaurants.settings.index', [
            'title' => 'Settings',
            'store' => $store,
            'root_name' => 'Settings',
            'sanboxNumber' => $sanboxNumber,
            'bankDetail' => $bankDetail,

        ]);
    }

    public function notification()
    {
        $notification = array();
        if (Auth::user()->subscription_end_date < date('Y-m-d')) {
            $notification['head'] = "YOUR SUBSCRIPTION HAS EXPIRED";
            $notification['sub_head'] = "Please renew your subscription to continue enjoying our services.";
            $notification['route_submit_button_name'] = "Renew Now";
            $notification['route'] = "store_admin.subscription_plans";
        }
        return $notification;
    }

    // analytics
    public function analytics()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        //        return $customers[0]->total_orders(9544752154);
        return view('restaurants.analytics.index', [
            'title' => 'Analytics',
            'root_name' => 'Analytics',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function getAnalytics()
    {
        // /** Todays Date */
        // $today_date = Carbon::now()->format('Y-m-d');

        // /** Creating from, to array Date */
        // $dt = Carbon::create(1975, 12, 25, 22, 0, 0);
        // $dt->toTimeString();
        // for ($i = 0; $i < 12; $i++) {
        //     $from[] = $dt->addHours(2)->format('H:i:s');
        // }

        // $dt = Carbon::create(1975, 12, 25, 0, 0, 0);
        // $dt->toTimeString();
        // for ($i = 0; $i < 12; $i++) {
        //     $to[] = $dt->addHours(2)->format('H:i:s');
        // }

        // /** Creating Label, Lists, Journals for today */
        // for ($i = 0; $i < 12; $i++) {
        //     $time[] = $from[$i] . " - " . $to[$i];
        //     $lists_today[] = Liste::whereBetween('created_at', [$today_date . ' ' . $from[$i], $today_date . ' ' . $to[$i]])->count();
        //     $journals_today[] = Journal::whereBetween('created_at', [$today_date . ' ' . $from[$i], $today_date . ' ' . $to[$i]])->count();
        // }
        // $list_today = new ChartJs;
        // $list_today->labels($time);
        // $list_today->dataset('Lists', 'bar', $lists_today)->options([
        //     'backgroundColor' => [
        //         '#4433FF',
        //         '#00D660',
        //         '#FF1F1F',
        //         '#FF8E24',
        //         '#060606',
        //         '#F5E2E3',
        //         '#566FA9'
        //     ]
        // ]);
        // $journal_today = new ChartJs;
        // $journal_today->labels($time);
        // $journal_today->dataset('Journals', 'bar', $journals_today)->options([
        //     'backgroundColor' => [
        //         '#4433FF',
        //         '#00D660',
        //         '#FF1F1F',
        //         '#FF8E24',
        //         '#060606',
        //         '#F5E2E3',
        //         '#566FA9'
        //     ]
        // ]);


        // /** Creating labels, journals and lists for past 7 days */
        // for ($i = 1; $i < 7; $i++) {
        //     $date[] = Carbon::now()->subDays(7 - $i)->format('d-m-Y');
        //     $lists_7_days[] = Liste::whereDate('created_at', Carbon::now()->subDays(7 - $i)->format('Y-m-d'))->count();
        //     $journals_7_days[] = Journal::whereDate('created_at', Carbon::now()->subDays(7 - $i)->format('Y-m-d'))->count();
        // }
        // $list_7_days = new ChartJs;
        // $list_7_days->labels($date);
        // $list_7_days->dataset('Lists', 'bar', $lists_7_days)->options([
        //     'backgroundColor' => [
        //         '#4433FF',
        //         '#00D660',
        //         '#FF1F1F',
        //         '#FF8E24',
        //         '#060606',
        //         '#F5E2E3',
        //         '#566FA9'
        //     ]
        // ]);
        // $journal_7_days = new ChartJs;
        // $journal_7_days->labels($date);
        // $journal_7_days->dataset('Journals', 'bar', $journals_7_days)->options([
        //     'backgroundColor' => [
        //         '#4433FF',
        //         '#00D660',
        //         '#FF1F1F',
        //         '#FF8E24',
        //         '#060606',
        //         '#F5E2E3',
        //         '#566FA9'
        //     ]
        // ]);

        // return view('dashboard.index', ['list_7_days' => $list_7_days, 'journal_7_days' => $journal_7_days, 'list_today' => $list_today, 'journal_today' => $journal_today]);

        // $customers = Order::all()->sortByDesc('id')->unique('customer_phone')->where('store_id', '=', auth()->id());
        // $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        // //        return $customers[0]->total_orders(9544752154);
        // return view('restaurants.customers.index', [
        //     'title' => 'Customers',
        //     'customers' => $customers,
        //     'root_name' => 'Customers',
        //     'sanboxNumber' => $sanboxNumber,
        // ]);
    }

    public function waiter_calls()
    {
        if(!Auth::user()->can('view_waiter_call')){
            return redirect()->route('store_admin.dashboard');
        }
        $calls = WaiterCall::all()->where('store_id', '=', auth()->id())->sortByDesc('id');
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

        //        return $customers[0]->total_orders(9544752154);
        return view('restaurants.waiterCall.view', [
            'title' => 'Customers',
            'count' => $calls->where('is_completed', '=', 0)->count(),
            'calls' => $calls,
            'root_name' => 'Waiter Call',
            'sanboxNumber' => $sanboxNumber,
            'notification' => $this->notification(),
            "order_count" => Order::all()->where('store_id', '=', auth()->id())->count(),
            'call_waiter_count' => WaiterCall::all()->where('store_id', '=', auth()->id())->count(),
        ]);
    }

    public function addwaiters()
    {
        if(!Auth::user()->can('add_waiter')){
            return redirect()->route('store_admin.dashboard');
        }
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.waiters.addwaiters', ['root_name' => 'Waiter', 'sanboxNumber' => $sanboxNumber,]);
    }

    public function waiters()
    {
        if(!Auth::user()->can('view_waiter')){
            return redirect()->route('store_admin.dashboard');
        }

        $waiters = Waiter::with('store_tables')->get()->sortByDesc('id')->where('store_id', '=', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.waiters.index', [
            'title' => 'Waiters',
            'waiters' => $waiters,
            'root_name' => 'Waiters',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function allwaitershifts()
    {
        if(!Auth::user()->can('view_waiter')){
            return redirect()->route('store_admin.dashboard');
        }
        // $waiters = Waiter::with('store_tables')->get()->sortByDesc('id')->where('store_id', '=', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.waiters.waiter_shifts', [
            'title' => 'Waiter Shifts',
            'root_name' => 'Waiter Shifts',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function editwaiters(Waiter $waiter)
    {
        if(!Auth::user()->can('add_waiter')){
            return redirect()->route('store_admin.dashboard');
        }
        $head_name = "Update Waiter";
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.waiters.editwaiters', compact('waiter'), [
            'title' => 'Waiter',
            'root_name' => 'Waiter',
            'root' => 'Waiter',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function kitchens()
    {
        if(!Auth::user()->can('view_kitchen')){
            return redirect()->route('store_admin.dashboard');
        }
        $kitchens = Kitchen::all()->sortByDesc('id')->where('store_id', '=', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.kitchens.index', [
            'title' => 'Kitchens',
            'kitchens' => $kitchens,
            'root_name' => 'Kitchens',
            'sanboxNumber' => $sanboxNumber,
            'notification' => $this->notification(),
            "order_count" => Order::all()->where('store_id', '=', auth()->id())->count(),
            'call_waiter_count' => WaiterCall::all()->where('store_id', '=', auth()->id())->count(),
        ]);
    }

    public function addkitchens()
    {
        if(!Auth::user()->can('add_kitchen')){
            return redirect()->route('store_admin.dashboard');
        }
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.kitchens.addkitchens', ['root_name' => 'Waiter', 'sanboxNumber' => $sanboxNumber,]);
    }

    public function editkitchens(Kitchen $kitchen)
    {
        if(!Auth::user()->can('add_kitchen')){
            return redirect()->route('store_admin.dashboard');
        }
        $head_name = "Update Kitchen";
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.kitchens.editkitchens', compact('kitchen'), [
            'title' => 'Kitchen',
            'root_name' => 'Kitchen',
            'root' => 'Kitchen',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function changePassword(Kitchen $kitchen)
    {
        $head_name = "Change Password";
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.kitchens.changePassword', compact('kitchen'), [
            'title' => 'Kitchen',
            'root_name' => 'Kitchen',
            'root' => 'Kitchen',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }




    public function discount()
    {
        $discounts = Discount::all()->SortByDesc('id')->where('store_id', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.discount.discount', [
            'title' => 'All Tables',
            'discounts' => $discounts,
            'root_name' => 'Discounts and Banners',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function editdiscount($id)
    {
        $discount = Discount::findOrFail($id);
        $time_restrictions = TimeRestriction::all()->where('store_id', auth()->id());
        $products = Product::all()->where('store_id', auth()->id());
        // $head_name = "Update Banner";
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.discount.editdiscount', [
            'title' => 'Edit Discount',
            'root_name' => 'Edit Discount',
            'root' => 'Edit Discount',
            'sanboxNumber' => $sanboxNumber,
            'discount' => $discount,
            'time_restrictions' => $time_restrictions,
            'products' => $products,
        ]);
    }




    public function adddiscount()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $time_restrictions = TimeRestriction::all()->where('store_id', auth()->id());
        $products = Product::all()->where('store_id', auth()->id());
        return view('restaurants.discount.adddiscount', [
            'title' => 'Add Discounts',
            'root_name' => 'Discounts',
            'sanboxNumber' => $sanboxNumber,
            'time_restrictions' => $time_restrictions,
            'products' => $products,
        ]);
    }

    public function coupon()
    {
        $coupons = Coupon::all()->SortByDesc('id')->where('store_id', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.coupon.coupon', [
            'title' => 'All Coupon',
            'coupons' => $coupons,
            'root_name' => 'Discounts and Banners',
            'sanboxNumber' => $sanboxNumber,
        ]);
    }

    public function addcoupon()
    {
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        // $time_restrictions = TimeRestriction::all()->where('store_id', auth()->id());
        $products = Product::all()->where('store_id', auth()->id());
        $categories = Category::all()->where('store_id', auth()->id());
        return view('restaurants.coupon.addcoupon', [
            'title' => 'Add Coupons',
            'root_name' => 'Add Coupons',
            'sanboxNumber' => $sanboxNumber,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function editcoupon(Coupon $coupon, Request $request)
    {
        $products = Product::all()->where('store_id', auth()->id());
        $categories = Category::all()->where('store_id', auth()->id());
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        return view('restaurants.coupon.editcoupon', [
            'title' => 'Edit Coupon',
            'root_name' => 'Edit Coupon',
            'root' => 'Edit Coupon',
            'sanboxNumber' => $sanboxNumber,
            'coupon' => $coupon,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function restaurantsAddProducts(){
        return view('restaurants.addproducts');
    }
    public function restaurantsOrders(){
        return view('restaurants.orders');
    }
    public function restaurantsViewOrder(){
        return view('restaurants.vieworder');
    }
}
