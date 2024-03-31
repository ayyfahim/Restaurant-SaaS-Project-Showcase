<?php

namespace App\Http\Controllers\WEBAPI;

use App\Slider;
use App\Waiter;
use App\Product;
use App\Category;
use Carbon\Carbon;
use App\Application;
use App\Models\Addon;
use App\Models\Order;
use App\Models\Store;
use App\Models\Table;
use Cmixin\BusinessTime;
use App\Models\WaiterCall;
use App\Models\StoreSlider;
use Illuminate\Http\Request;
use App\Models\AddonCategory;
use App\Models\AddonCategoryItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TranslationService;
use App\Http\Controllers\Notification\NotificationController;
use App\Menu;
use App\NestedAddon;
use Storage;

class StoreController extends Controller
{
    public function fetch(Request $request)
    {

        $view_id =  $request->view_id;
        if (Store::all()->where('view_id', '=', $view_id)->count() == 0) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "error" => [
                    "code" => 401,
                    "type" => "data not found (ERROR:RT404)",
                    "message" => "We are unable to process your request at this time. Please try again later"
                ],
            ], 401);
        }
        if (Store::all()->where('view_id', '=', $view_id)->where('is_visible', '=', 1)->count() == 0)
            return view('Home.404');
        $store = Store::all()->where('view_id', '=', $view_id)->first();
        $store_id  = $store['id'];
        $store_name  = $store['store_name'];
        $store_address  = $store['address'];
        $store_logo  = Storage::disk('s3')->url($store['logo_url']);
        $store_logo_wide  = Storage::disk('s3')->url($store['logo_url_wide']);
        $is_accept_order  = $store['is_accept_order'];
        $pay_first  = $store['pay_first'];
        $description  = $store['description'];
        $store_latitude  = $store['address_latitude'];
        $store_longitude  = $store['address_longitude'];
        $is_location_required  = (bool) $store['location_required'];
        $order_range  = (int) $store['order_range'];
        $sliders_data = StoreSlider::all()->where('is_visible', '=', 1)->where('store_id', '=', $store_id);
        $table_data = Table::all()->where('is_active', '=', 1)->where('store_id', '=', $store_id);
        $tables = [];
        $sliders = [];
        foreach ($table_data as $value)
            $tables[] = $value;
        foreach ($sliders_data as $value)
            $sliders[] = $value;
        $recommended_data = Product::with(['addonItems.categories.addons.nested_addons.addon_category.addons', 'time_restrictions', 'discounts.time_restrictions', 'allergens', 'addonItems.categories.nested_addons'])
            ->where('store_id', '=', $store_id)
            ->where('is_recommended', '=', 1)
            ->where('is_active', '=', 1)->orderBy('index_number')->get();
        $recommended = [];
        foreach ($recommended_data as $value) {
            $recommended[] = $value;
        }

        $food_menu_data = Menu::all()->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1);
        $food_menus = [];
        $translations = [];
        foreach ($food_menu_data as $value) {
            $translation = new TranslationService();
            $translations[$value->id] = $translation->selected_menu_translations($value->id);
            $food_menus[] = $value;
        }

        $categories_data = Category::with('products')->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1)->get()->sortBy('index_number');
        $categories = [];
        foreach ($categories_data as $value) {
            $value['has_product'] = $value->products()->exists();
            $categories[] = $value;
        }

        $products_data = Product::with(['addonItems.categories.addons.nested_addons.addon_category.addons', 'time_restrictions', 'discounts.time_restrictions', 'allergens', 'addonItems.categories.nested_addons'])->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1)->orderBy('index_number')->get();
        $products = [];
        foreach ($products_data as $value) {
            $products[] = $value;
        }
        if ($store['currency_symbol'] != NULL) {
            $account_info = Application::all()->first();
            $account_info['currency_symbol'] = $store['currency_symbol'];
        } else
            $account_info = Application::all()->first();

        $Addon_product = Addon::with('nested_addons.addon_category')->where('store_id', '=', $store_id)->where('is_active', '=', 1)->get();
        $addons = [];
        foreach ($Addon_product as $value)
            $addons[] = $value;

        $getAllAddonCategories = AddonCategory::with('addons.nested_addons.addon_category')
            ->where('store_id', '=', $store_id)
            ->where('is_active', '=', 1)
            ->get();
        $addon_categories = [];
        foreach ($getAllAddonCategories as $value)
            $addon_categories[] = $value;

        $Nested_addon_product = NestedAddon::all()->where('store_id', '=', $store_id);
        $nested_addon = [];
        foreach ($Nested_addon_product as $value)
            $nested_addon[] = $value;

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => [
                    'recommended' => $recommended,
                    'food_menus' => $food_menus,
                    'categories' => $categories,
                    'products' => $products,
                    'account_info' => $account_info,
                    'store_name' => $store_name,
                    'store_logo' => $store_logo,
                    'store_address' => $store_address,
                    'store_logo_wide' => $store_logo_wide,
                    'description' => $description,
                    'sliders' => $sliders,
                    'translations' => $translations,
                    'tables' => $tables,
                    'is_accept_order' => $is_accept_order,
                    'pay_first' => $pay_first,
                    'service_charge' => $store['service_charge'],
                    'tax' => $store['tax'],
                    'addons' => $addons,
                    'addon_categories' => $addon_categories,
                    'nested_addons' => $nested_addon,
                    'store_latitude' => $store_latitude,
                    'store_longitude' => $store_longitude,
                    'is_location_required' => $is_location_required,
                    'order_range' => $order_range,

                ],
            ]
        ], 200);
    }

    public function get_product_is_availiable($value)
    {
        $start_time_end_time = Carbon::parse($value->time_restrictions->first()->data['start_time'])->format('H:i') . "-" . Carbon::parse($value->time_restrictions->first()->data['end_time'])->format('H:i');

        BusinessTime::enable(Carbon::class, [
            'monday' => [$start_time_end_time],
            'tuesday' =>
            [$start_time_end_time],
            'wednesday' =>
            [$start_time_end_time],
            'thursday' =>
            [$start_time_end_time],
            'friday' =>
            [$start_time_end_time],
            'saturday' =>
            [$start_time_end_time],
            'sunday' =>
            [$start_time_end_time],
        ]);

        return Carbon::now()->isOpen();
    }

    public function get_product_discount($value)
    {
        $start_time_end_time = Carbon::parse($value->discounts->first()->time_restrictions->first()->data['start_time'])->format('H:i') . "-" . Carbon::parse($value->discounts->first()->time_restrictions->first()->data['end_time'])->format('H:i');
        BusinessTime::enable(Carbon::class, [
            'monday' => [$start_time_end_time],
            'tuesday' =>
            [$start_time_end_time],
            'wednesday' =>
            [$start_time_end_time],
            'thursday' =>
            [$start_time_end_time],
            'friday' =>
            [$start_time_end_time],
            'saturday' =>
            [$start_time_end_time],
            'sunday' =>
            [$start_time_end_time],
        ]);

        return Carbon::now()->isOpen();
    }

    public function calling_waiter(Request $request)
    {
        $title = "Waiter Call";
        $notification = new NotificationController();
        $data = $request->all();

        if ($request->order_id && $request->type) {
            switch ($request->method) {
                case 'isCard':

                    $order = Order::all()->find($request->order_id);
                    // $body = $order['table_no'] != NULL ? "Table #{$order['table_no']} {$order['customer_name']} ({$order['order_unique_id']}) wants to pay with Card."
                    //     : "#{$order['table_no']} {$order['customer_name']} ({$order['order_unique_id']}) wants to pay with Card.";
                    $body = $order['table_no'] != NULL ? "Table #{$order['table_no']} wants to pay with Card."
                        : "Table #{$order['table_no']} wants to pay with Card.";
                    try {
                        $notification->send_notification($title, $body, $order['store_id']);
                    } catch (\Exception $e) {
                    }
                    $data['customer_name'] = $order['customer_name'];
                    $data['customer_phone'] = $order['customer_phone'];
                    $data['table_name'] = $order['table_no'];
                    $data['comment'] = $body;
                    $data['store_id'] = $order['store_id'];
                    $data['order_id'] = $order['id'];
                    $data['user_id'] = \auth()->id() ?? null;
                    unset($data['method']);
                    WaiterCall::create($data);

                    return response()->json([
                        "success" => true,
                        "status" => "success",
                        "payload" => [
                            'data' => []

                        ]
                    ], 200);

                    break;

                case 'isCash':

                    $order = Order::all()->find($request->order_id);
                    // $body = $order['table_no'] != NULL ? "Table #{$order['table_no']} {$order['customer_name']} ({$order['order_unique_id']}) wants to pay with Cash."
                    //     : "#{$order['table_no']} {$order['customer_name']} ({$order['order_unique_id']}) wants to pay with Cash.";
                    $body = $order['table_no'] != NULL ? "Table #{$order['table_no']} wants to pay with Cash."
                        : "Table #{$order['table_no']} wants to pay with Cash.";
                    try {
                        $notification->send_notification($title, $body, $order['store_id']);
                    } catch (\Exception $e) {
                    }
                    $data['customer_name'] = $order['customer_name'];
                    $data['customer_phone'] = $order['customer_phone'];
                    $data['table_name'] = $order['table_no'];
                    $data['comment'] = $body;
                    $data['store_id'] = $order['store_id'];
                    $data['order_id'] = $order['id'];
                    $data['user_id'] = \auth()->id() ?? null;
                    unset($data['method']);
                    WaiterCall::create($data);

                    return response()->json([
                        "success" => true,
                        "status" => "success",
                        "payload" => [
                            'data' => []

                        ]
                    ], 200);

                    break;

                default:
                    return response()->json([
                        "success" => true,
                        "status" => "error",
                        "msg" => "No payment type called.",
                        "payload" => [
                            'data' => []

                        ]
                    ], 422);
                    break;
            }
        }

        if ($request->order_id) {
            $order = Order::all()->find($request->order_id);
            $body = $order['table_no'] != NULL ? "Table #{$order['table_no']} calling the waiter"
                : "#{$order['table_no']} calling the waiter";
            try {
                $notification->send_notification($title, $body, $order['store_id']);
            } catch (\Exception $e) {
            }

            if (auth()->user()) {
                $last_waiter_call = \auth()->user()->waiter_calls->where('type', 0)->last();
                if ($last_waiter_call && $last_waiter_call->created_at->diffInMinutes() < 5) {
                    return response()->json([
                        "success" => false,
                        "status" => "failed",
                        "message" => "Please try again after " . strval(5 - $last_waiter_call->created_at->diffInMinutes()) . " minutes",
                        "payload" => [
                            'data' => []

                        ]
                    ], 422);
                }
            }

            $data['customer_name'] = $order['customer_name'];
            $data['customer_phone'] = $order['customer_phone'];
            $data['table_name'] = $order['table_no'];
            $data['comment'] = $body;
            $data['store_id'] = $order['store_id'];
            $data['user_id'] = \auth()->id() ?? null;
            WaiterCall::create($data);
        } else {
            $data['store_id'] = Store::all()->where('view_id', '=', $request->store_id)->first()['id'];

            if ($request->quick_help) {
                switch ($request->quick_help) {
                    case 1:
                        $body = $request['table_name'] != NULL ? "Table #{$request['table_name']} ({$request['customer_name']}) wants help with the menu."
                            : "Table #{$request['table_name']} ({$request['customer_name']}) wants help with the menu.";
                        break;

                    case 2:
                        $body = $request['table_name'] != NULL ? "Table #{$request['table_name']} ({$request['customer_name']}) is having problem with his order."
                            : "Table #{$request['table_name']} ({$request['customer_name']}) is having problem with his order.";
                        break;

                    case 3:
                        $body = $request['table_name'] != NULL ? "Table #{$request['table_name']} ({$request['customer_name']}) is having problem with the system."
                            : "Table #{$request['table_name']} ({$request['customer_name']}) is having problem with the system.";
                        break;

                    default:
                        $body = $request['table_name'] != NULL ? "Table #{$request['table_name']} ({$request['customer_name']}) calling the waiter"
                            : "{$request['table_name']}{$request['customer_name']} calling the waiter";
                        break;
                }
                unset($data['quick_help']);
            } else {
                $body = $request['table_name'] != NULL ? "Table #{$request['table_name']} ({$request['customer_name']}) calling the waiter"
                    : "{$request['table_name']}{$request['customer_name']} calling the waiter";
            }

            // $body = $request['table_name'] != NULL ? "Table #{$request['table_name']} ({$request['customer_name']}) calling the waiter"
            //     : "{$request['table_name']}{$request['customer_name']} calling the waiter";

            try {
                $notification->send_notification($title, $body, $data['store_id']);
            } catch (\Exception $e) {
            }

            if (auth()->user()) {
                $last_waiter_call = \auth()->user()->waiter_calls->where('type', 0)->last();
                if ($last_waiter_call && $last_waiter_call->created_at->diffInMinutes() < 5) {
                    return response()->json([
                        "success" => false,
                        "status" => "failed",
                        "message" => "Please try again after " .  strval(5 - $last_waiter_call->created_at->diffInMinutes()) . " minutes",
                        "payload" => [
                            'data' => []

                        ]
                    ], 422);
                }
            }

            $data['user_id'] = \auth()->id() ?? null;
            $data['comment'] = $request->comment ? $request->comment : $body;
            WaiterCall::create($data);
        }
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => []

            ]
        ], 200);
    }

    public function all_translation(Request $request)
    {

        $translation = new TranslationService();
        $response = array();
        $translation_data = $translation->languages();

        foreach ($translation_data as $data)
            $response[] = $data;
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $response
            ]
        ], 200);
    }

    public function translation(Request $request)
    {

        $translation = new TranslationService();
        $response = array();
        $translation_data = $translation->selected_language_api($request->language_id, $request->store_id);


        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $translation_data
            ]
        ], 200);
    }

    public function all_waiter_shifts(Request $request)
    {
        // return \auth()->user();
        $store = Store::find($request->store_id);
        return $store->waiter_shifts->first()->data;
    }

    public function waiter_shift(Request $request, Waiter $waiter)
    {
        return $waiter->waiter_shift->data;
    }

    public function update_waiter_shifts(Request $request)
    {
        if (auth('store')->check()) {
            return false;
        }

        try {
            $date = Carbon::createFromFormat('g:i A', $request->cellData)->format('H:i:s');

            $waiter = Waiter::findOrFail($request->waiter_id);
            return $waiter->waiter_shift->update([
                'data' => $request->data
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function checkIfStoreAndTableExist(Request $request)
    {
        $store = Store::where('view_id', $request->store_id)->get()->first();

        $table = false;

        if ($store) {
            $table = Table::where('table_number', $request->table_id)->where('store_id', $store->id)->get()->first();
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "store_exist" => $store ? true : false,
            "table_exist" => $table ? true : false,
        ]);
    }
}
