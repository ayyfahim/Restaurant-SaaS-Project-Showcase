<?php

namespace App\Http\Controllers;

use App\Waiter;
use App\Kitchen;
use App\Product;
use App\Category;
use App\Application;
use App\Models\Addon;
use App\Models\Order;
use App\Models\Table;
use App\Models\Setting;
use App\KitchenLocation;
use App\Models\WaiterCall;
use App\Models\StoreSlider;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\AddonCategory;
use App\Models\AddonCategoryItem;
use App\Models\StoreSubscription;
use Illuminate\Support\Facades\DB;
use App\Models\SelectedSubscription;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;

class KitchenAdminPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:kitchen');
    }

    public function dashboard()
    {
        if (!auth()->user()->is_main) {
            return redirect()->route('kitchen_admin.kitchenlocation');
        }

        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        // $orders = Order::all()->SortByDesc('id')->where('store_id', auth()->user()->store_id);
        $store = Store::find(auth()->user()->store_id);
        $tables = Table::with('kitchen_orders.orderDetails.OrderDetailsExtraAddon')
            ->with([
                'kitchen_orders' => function ($query) use ($store) {
                    $query->where('status', '2')
                        ->limit($store->order_limit)
                        ->orderBy('id', 'desc');
                }
            ])
            ->with('store')
            ->where('store_id', auth()->user()->store_id)
            ->get()
            ->SortByDesc('id')
            ->toArray();
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->user()->store_id)->count();
        // $kitchenLocations = KitchenLocation::where('store_id', auth()->user()->store_id)->get()->SortByDesc('id');

        // dd($tables);

        return view('kitchens.dashboard', [
            'title' => 'Kitchen',
            // 'count' => $calls->where('is_completed', '=', 0)->count(),
            // 'calls' => $calls,
            'root_name' => 'Kitchen',
            'sanboxNumber' => $sanboxNumber,
            // 'orders' => $orders,
            'tables' => $tables,
            'orders_count' => $orders_count,
            // 'kitchenLocations' => $kitchenLocations,
            // 'call_waiter_count' => $call_waiter_count,
        ]);
    }

    public function authKitchenLocation(Request $request)
    {
        $id = auth()->id();
        $kitchenLocation = Kitchen::findOrFail($id);
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->user()->store_id)->count();
        // $tables = Table::with('kitchen_orders.orderDetails.OrderDetailsExtraAddon')->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->toArray();
        $tables = Table::with([
            'kitchen_orders.orderDetails.OrderDetailsExtraAddon' => function ($query) use ($id) {
                $query->where('kitchen_location_id', $id);
                $query->where('status', 0);
            },
        ])->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->toArray();
        // $kitchenLocations = KitchenLocation::where('store_id', auth()->user()->store_id)->get()->SortByDesc('id');

        foreach ($tables as $table_key => $table) {
            foreach ($table['kitchen_orders'] as $order_key => $order) {
                foreach ($order['order_details'] as $order_detail_key => $detail) {
                    if ($detail['kitchen_location_id'] != $id) {
                        if ($detail['order_details_extra_addon']) {
                            foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
                                if ($addon['kitchen_location_id'] == $id) {
                                    // dd($table['kitchen_orders'][$order_key]);
                                    unset($table['kitchen_orders'][$order_key]);
                                }
                            }
                        } else {
                            unset($tables[$table_key]['kitchen_orders'][$order_key]);
                        }
                    }
                    if ($detail['status'] == 1) {
                        if ($detail['order_details_extra_addon']) {
                            foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
                                if ($addon['status'] == 1) {
                                    unset($table['kitchen_orders'][$order_key]);
                                }
                            }
                        } else {
                            unset($tables[$table_key]['kitchen_orders'][$order_key]);
                        }
                    }
                }
            }
        }

        // foreach ($tables as $table_key => $table) {
        //     foreach ($table['kitchen_orders'] as $order_key => $order) {
        //         foreach ($order['order_details'] as $order_detail_key => $detail) {
        //             if ($detail['status'] == 1) {
        //                 if ($detail['order_details_extra_addon']) {
        //                     foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
        //                         if ($addon['status'] == 1) {
        //                             unset($table['kitchen_orders'][$order_key]);
        //                         }
        //                     }
        //                 } else {
        //                     unset($tables[$table_key]['kitchen_orders'][$order_key]);
        //                 }
        //             }
        //         }
        //     }
        // }

        // dd(json_encode($tables));


        return view('kitchens.kitchen_location', [
            'title' => 'Kitchen',
            'root_name' => 'Kitchen',
            'sanboxNumber' => $sanboxNumber,
            'tables' => $tables,
            'orders_count' => $orders_count,
            'kitchenLocation' => $kitchenLocation,
            // 'kitchenLocations' => $kitchenLocations,
        ]);
    }

    public function kitchenlocation(Request $request, int $id)
    {
        $kitchenLocation = Kitchen::findOrFail($id);
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->user()->store_id)->count();
        // $tables = Table::with('kitchen_orders.orderDetails.OrderDetailsExtraAddon')->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->toArray();
        $tables = Table::with([
            'kitchen_orders.orderDetails.OrderDetailsExtraAddon' => function ($query) use ($id) {
                $query->where('kitchen_location_id', $id);
                $query->where('status', 0);
            },
        ])->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->toArray();
        // $kitchenLocations = KitchenLocation::where('store_id', auth()->user()->store_id)->get()->SortByDesc('id');

        foreach ($tables as $table_key => $table) {
            foreach ($table['kitchen_orders'] as $order_key => $order) {
                foreach ($order['order_details'] as $order_detail_key => $detail) {
                    if ($detail['kitchen_location_id'] != $id) {
                        if ($detail['order_details_extra_addon']) {
                            foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
                                if ($addon['kitchen_location_id'] == $id) {
                                    // dd($table['kitchen_orders'][$order_key]);
                                    unset($table['kitchen_orders'][$order_key]);
                                }
                            }
                        } else {
                            unset($tables[$table_key]['kitchen_orders'][$order_key]);
                        }
                    }
                    if ($detail['status'] == 1) {
                        if ($detail['order_details_extra_addon']) {
                            foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
                                if ($addon['status'] == 1) {
                                    unset($table['kitchen_orders'][$order_key]);
                                }
                            }
                        } else {
                            unset($tables[$table_key]['kitchen_orders'][$order_key]);
                        }
                    }
                }
            }
        }

        // foreach ($tables as $table_key => $table) {
        //     foreach ($table['kitchen_orders'] as $order_key => $order) {
        //         foreach ($order['order_details'] as $order_detail_key => $detail) {
        //             if ($detail['status'] == 1) {
        //                 if ($detail['order_details_extra_addon']) {
        //                     foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
        //                         if ($addon['status'] == 1) {
        //                             unset($table['kitchen_orders'][$order_key]);
        //                         }
        //                     }
        //                 } else {
        //                     unset($tables[$table_key]['kitchen_orders'][$order_key]);
        //                 }
        //             }
        //         }
        //     }
        // }

        // dd($tables);


        return view('kitchens.kitchen_location', [
            'title' => 'Kitchen',
            'root_name' => 'Kitchen',
            'sanboxNumber' => $sanboxNumber,
            'tables' => $tables,
            'orders_count' => $orders_count,
            'kitchenLocation' => $kitchenLocation,
            // 'kitchenLocations' => $kitchenLocations,
        ]);
    }

    // public function waiter_calls()
    // {
    //     // also table id
    //     $calls = auth()->user()->waiter_calls();
    //     $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
    //     $call_waiter_count = WaiterCall::all()->where('store_id', '=', auth()->user()->store_id)->count();

    //     return view('waiters.waiter_calls', [
    //         'title' => 'Waiter Call',
    //         'count' => $calls->where('is_completed', '=', 0)->count(),
    //         'calls' => $calls,
    //         'root_name' => 'Waiter Call',
    //         'sanboxNumber' => $sanboxNumber,
    //         'call_waiter_count' => $call_waiter_count,
    //     ]);
    // }

    // public function order_requests()
    // {
    //     // also table id
    //     $calls = auth()->user()->order_requests();
    //     $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
    //     $call_waiter_count = WaiterCall::all()->where('store_id', '=', auth()->user()->store_id)->count();

    //     return view('waiters.order_requests', [
    //         'title' => 'Order Requests',
    //         'count' => $calls->where('is_completed', '=', 0)->count(),
    //         'calls' => $calls,
    //         'root_name' => 'Order Requests',
    //         'sanboxNumber' => $sanboxNumber,
    //         'call_waiter_count' => $call_waiter_count,
    //     ]);
    // }

    // public function waiter_calls_old()
    // {
    //     $calls = WaiterCall::all()->where('store_id', '=', auth()->id())->sortByDesc('id');
    //     $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;

    //     //        return $customers[0]->total_orders(9544752154);
    //     return view('restaurants.waiterCall.view', [
    //         'title' => 'Customers',
    //         'count' => $calls->where('is_completed', '=', 0)->count(),
    //         'calls' => $calls,
    //         'root_name' => 'Waiter Call',
    //         'sanboxNumber' => $sanboxNumber,
    //     ]);
    // }
}
