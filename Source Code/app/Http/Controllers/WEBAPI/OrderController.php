<?php

namespace App\Http\Controllers\WEBAPI;

use App\Coupon;
use App\Product;
use App\Models\Addon;
use App\Models\Order;
use App\Models\Store;
use App\Models\Table;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\OrderDetailAddon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\API\DeliverectController;
use App\Models\Customer;
use Arr;
use Carbon\Carbon;

use function GuzzleHttp\json_decode;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        if (!auth('customer')->check()) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'Please log in.',
                ]
            ], 422);
        }

        $data = $request->all();
        $orderItems = $request->cart;
        unset($data['cart']);
        unset($data['couponCode']);

        $store = Store::all()->where('view_id', '=', $request->store_id)->first();

        if (count($orderItems) > $store->order_limit) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'You can\'t order more than ' . $store->order_limit . " items.",
                ]
            ], 422);
        }
        $data['store_id'] = $store->id;
        $data['order_unique_id'] = "ODR-" . time();
        $data['customer_name'] = request()->user()->first_name . ' ' . request()->user()->last_name;
        $data['customer_phone'] = request()->user()->phone;
        $data['customer_id'] = request()->user()->id;
        $data['table_no'] = $store->tables()->inRandomOrder()->first() ? $store->tables()->inRandomOrder()->first()->id : null;
        $data['discount'] = floatval($request->discount);
        $data['coupon'] = floatval($request->coupon);

        if ($store->pay_first) {
            $data['status'] = 3;
        }

        if (!$store->pay_first) {
            if($store->auto_accept_order) {
                $data['status'] = 2;
                $data['accepted_at'] = Carbon::now();
            }
        }

        $new_order = Order::create($data);

        $new_order['status'] = 1;

        if ($store->pay_first) {
            $new_order['status'] = 3;
        }

        if (!$store->pay_first) {
            if($store->auto_accept_order) {
                $new_order['status'] = $store->auto_accept_order ? 2 : 1;
                $new_order['accepted_at'] = $store->auto_accept_order ? Carbon::now() : null;
            }
        }

        $notification = new NotificationController();

        if ($new_order) {
            $order_id = Order::where('order_unique_id', '=', $data['order_unique_id'])->with('table')->first()['id'];
            $items = array();
            foreach ($orderItems as $value) {

                $temp = [];
                $temp['order_id'] = $order_id;
                $product = Product::all()->where('id', '=', $value['itemId'])->first();


                $temp['name'] = $product['name'];
                $temp['price'] = $product['price'];
                $temp['kitchen_location_id'] = $product->kitchen_location_id;

                $temp['quantity'] = $value['count'];
                $temp['status'] = 0;
                $temp['sku'] = $product->sku;

                $orderDetail = OrderDetails::create($temp);
                $createdAddons = [];

                if ($value['extra'] != null) {

                    $temp = array();
                    foreach ($value['extra'] as $value_extra) {
                        $addon = Addon::find($value_extra['addon_id']);
                        $temp['order_detail_id'] = $orderDetail->id;
                        $temp['addon_id'] = $addon->id;
                        $temp['addon_name'] = $addon->addon_name;
                        $temp['addon_price'] = $addon->price;
                        $temp['addon_count'] = $value_extra['count'];
                        $temp['kitchen_location_id']
                            = $addon->kitchen_location_id;
                        $temp['status'] = 0;
                        $temp['sku'] = $addon->sku;
                        $addon = OrderDetailAddon::create($temp);
                        array_push($createdAddons, $addon);
                    }
                }

                if (isset($value['addon'])) {
                    $temp = array();
                    foreach ($value['addon'] as $addon_id) {
                        $addon = Addon::find($addon_id);
                        $temp['order_detail_id'] = $orderDetail->id;
                        $temp['addon_id'] = $addon->id;
                        $temp['addon_name'] = $addon->addon_name;
                        $temp['addon_price'] = $addon->price;
                        $temp['addon_count'] = 1;
                        $temp['kitchen_location_id']
                            = $addon->kitchen_location_id;
                        $temp['status'] = 0;
                        $temp['sku'] = $addon->sku;
                        $addon = OrderDetailAddon::create($temp);
                        array_push($createdAddons, $addon);
                    }
                }

                if (isset($value['nestedAddon']) && !empty($value['nestedAddon'])) {
                    $temp = array();
                    foreach ($value['nestedAddon']['extra'] as $nested_addons) {
                        $nested_addon = Addon::find($nested_addons['addon_id']);
                        foreach ($createdAddons as $addonValue) {
                            if ($addonValue['addon_id'] == $nested_addons['parentAddonId'] && $addonValue['order_detail_id'] == $orderDetail->id) {
                                $parent_addon_id = $addonValue['id'];
                                $temp['parent_addon_id'] = $parent_addon_id;
                            }
                        }
                        $temp['order_detail_id'] = $orderDetail->id;
                        $temp['kitchen_location_id'] = $nested_addon->kitchen_location_id;
                        $temp['status'] = 0;
                        $temp['addon_id'] = $nested_addon->id;
                        $temp['addon_name'] = $nested_addon->addon_name;
                        $temp['addon_price'] = $nested_addon->price;
                        $temp['addon_count'] = $nested_addons['count'] ?? 1;
                        $temp['sku'] = $nested_addon->sku;
                        OrderDetailAddon::create($temp);
                    }
                    foreach ($value['nestedAddon']['addon'] as $nested_addons) {
                        $nested_addon = Addon::find($nested_addons['addon_id']);
                        foreach ($createdAddons as $addonValue) {
                            if ($addonValue['addon_id'] == $nested_addons['parentAddonId'] && $addonValue['order_detail_id'] == $orderDetail->id) {
                                $parent_addon_id = $addonValue['id'];
                                $temp['parent_addon_id'] = $parent_addon_id;
                            }
                        }
                        $temp['order_detail_id'] = $orderDetail->id;
                        $temp['kitchen_location_id'] = $nested_addon->kitchen_location_id;
                        $temp['status'] = 0;
                        $temp['addon_id'] = $nested_addon->id;
                        $temp['addon_name'] = $nested_addon->addon_name;
                        $temp['addon_price'] = $nested_addon->price;
                        $temp['addon_count'] = $nested_addons['count'] ?? 1;
                        $temp['sku'] = $nested_addon->sku;
                        OrderDetailAddon::create($temp);
                    }
                }
            }

            // All the orders that are not served by kitchen will be assigned the same group id
            $unpaid_orders = $new_order->table ? $new_order->table->kitchen_orders : null;

            if($unpaid_orders){
                if ($unpaid_orders->count() > 0) {
                    $group_id = $unpaid_orders->first()->order_group_id ? $unpaid_orders->first()->order_group_id : "ODRGRP" . time();

                    $new_order->order_group_id = $group_id;
                    $new_order->save();
                } else {
                    $group_id = "ODRGRP" . time();
                    $new_order->order_group_id = $group_id;
                    $new_order->save();
                }
            }

            $response_data = Order::all()->where('customer_phone', '=', $request->customer_phone);

            $response = [];
            foreach ($response_data as $value) {
                $response[] = $value;
            }

            $get_new_order = Order::with('orderDetails.OrderDetailsExtraAddon')->where('id', $new_order->id)->get();
            if ($get_new_order->first()->status == '2') {
                try {
                    $deliverectController = new  DeliverectController();
                    $order_status = $deliverectController->createDeliverectOrder($get_new_order->first());
                    // $this->createDeliverectOrder($get_new_order->first());
                } catch (\Throwable $e) {
                    Log::error($e, ["store_id" => $store->id, "order_id" => $get_new_order->first()->id]);
                }
            }

            if ($coupon = Coupon::where('code', $request->couponCode)->first()) {
                $request->user()->coupons()->attach($coupon);
            }

            $notification->WhatsAppOrderNotification($get_new_order->toArray());

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'user_orders' => $response,
                    'selectedOrder' => $get_new_order,
                ]
            ], 200);
        }
    }

    public function createDeliverectToken($order)
    {
        $store = Store::findOrFail($order->store_id);

        $response = Http::post(
                'https://api.staging.deliverect.com/oauth/token',
                [
                    "client_id"=> $store->deliverect_api_key,
                    "client_secret"=> $store->deliverect_api_secret_key,
                    "audience"=> "https://api.staging.deliverect.com",
                    "grant_type"=> "client_credentials"
                ]
            );

        $body = (string) $response->getBody();

        if ($response->failed()) {
            Log::debug($body, ["order_id" => $order->id]);
        }

        return [
            "access_token" => json_decode($body)->access_token,
            "location" => $store->deliverect_channel_link_id
        ];
    }

    // public function createDeliverectOrder($order)
    // {
    //     $get_token = $this->createDeliverectToken($order);

    //     foreach ($order->orderDetails as $item) {
    //         $subItems = [];

    //         foreach ($item->OrderDetailsExtraAddon as $addon) {
    //             $subItems[] = [
    //                 "plu"=> (string) $addon->sku,
    //                 "name"=> (string) $addon->addon_name,
    //                 "price"=> (float) $this->toFixed($addon->addon_price, 2) * 100,
    //                 "quantity"=> (int) $addon->addon_count,
    //                 "remark"=> "",
    //                 "subItems"=> []
    //             ];
    //         }

    //         $items[] = [
    //             "plu"=> (string) $item->sku,
    //             "name"=> (string) $item->name,
    //             "price"=> (float) $this->toFixed($item->price, 2) * 100,
    //             "quantity"=> (int) $item->quantity,
    //             "remark"=> "",
    //             "subItems" => $subItems
    //         ];
    //     }
    //     $user = Customer::find($order->customer_id);

    //     $array = [
    //         "channelOrderId" => (string) $order->order_unique_id,
    //         "channelOrderDisplayId"=> (string) $order->id,
    //         "channelLinkId"=> $get_token["location"],
    //         "by"=> "",
    //         "orderType"=> 3,
    //         "channel"=> 10000,
    //         "pickupTime"=> now()->toIso8601ZuluString(),
    //         "estimatedPickupTime"=> now()->toIso8601ZuluString(),
    //         "deliveryTime"=> now()->toIso8601ZuluString(),
    //         "deliveryIsAsap"=> true,
    //         "courier"=> "restaurant",
    //         "customer"=> [
    //             "name" => $user ? $user->first_name . ' ' . $user->last_name : "No Name Provided",
    //             "companyName"=> "Deliverect",
    //             "phoneNumber"=> $user->phone ?? "No Phone Provided",
    //             "email"=>  $user->email ?? "No Email Provided"
    //         ],
    //         "deliveryAddress"=> [
    //             "street"=> "The Krook",
    //             "streetNumber"=> "4",
    //             "postalCode"=> "9000",
    //             "city"=> "Gent",
    //             "extraAddressInfo"=> ""
    //         ],
    //         "orderIsAlreadyPaid"=> false,
    //         "payment"=> [
    //             "amount"=> (float) $this->toFixed($order->total, 2) * 100,
    //             "type"=> 0
    //         ],
    //         "note"=>  $order->comments ?? null,
    //         "items"=> $items,
    //         "decimalDigits"=> 2,
    //         "numberOfCustomers"=> 1,
    //         "deliveryCost"=> 0,
    //         "serviceCharge"=> 0,
    //         "discountTotal"=> $order->discount ? (float) $this->toFixed($order->discount, 2) * 100 : 0,
    //         "tip"=> 0
    //     ];

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $get_token["access_token"],
    //         'Content-Type' => 'application/json; charset=utf-8',
    //         'Accept' => 'application/json; charset=utf-8', // Get
    //     ])
    //         ->post(
    //             'https://api.staging.deliverect.com/appetizr/order/' .  $get_token["location"],
    //             $array
    //         );

    //     if ($response->failed()) {
    //         Log::error((string) $response->getBody(), ["order_id" => $order->id]);
    //     }

    //     return $response;
    // }

    public function fetch(Request $request)
    {
        $response_data = Order::with('orderDetails.OrderDetailsExtraAddon')
        ->where('customer_id', '=', $request->user()->id)
        // ->orWhere('customer_name', '=', $request->user()->name ?? time())
        // ->orWhere('customer_phone', '=', $request->user()->phone ?? time())
        ->get()->sortByDesc('id');
        $response = [];

        foreach ($response_data as $value) {
            $value['store_name'] = Store::all()->where('id', '=', $value['store_id'])->first()['store_name'];
            $response[] = $value;
        }

        $table_orders = null;
        $table_orders_response = [];

        // if ($request->table_no != null) {
        //     if ($last_table = Table::find($request->table_no)) {
        //         if ($last_table->unpaid_orders->count() > 0) {
        //             $last_table_order = $last_table->orders->last();

        //             if ($last_table_order) {
        //                 $table_orders = Order::with('orderDetails.OrderDetailsExtraAddon')->where('order_group_id', '=', $last_table_order->order_group_id)->get()->sortByDesc('id');

        //                 foreach ($table_orders as $value) {
        //                     $value['store_name'] = Store::all()->where('id', '=', $value['store_id'])->first()['store_name'];
        //                     $table_orders_response[] = $value;
        //                 }
        //             }
        //         }
        //     }
        // }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                // 'if' => $last_table->kitchen_orders,
                'user_orders' => $response,
                // 'table_orders' => $table_orders_response,
            ]
        ], 200);
    }

    public function fetchTableOrder(Request $request)
    {
        $table_orders = null;
        $table_orders_response = [];

        if ($request->table_no != null) {
            $last_table = Table::find($request->table_no);

            if ($last_table) {
                if ($last_table->unpaid_orders->count() > 0) {
                    $last_table_order = $last_table->orders->last();

                    if ($last_table_order && $last_table_order->order_group_id) {
                        $table_orders = Order::with('orderDetails.OrderDetailsExtraAddon')->where('order_group_id', '=', $last_table_order->order_group_id)->get()->sortByDesc('id');

                        foreach ($table_orders as $value) {
                            $value['store_name'] = Store::all()->where('id', '=', $value['store_id'])->first()['store_name'];
                            $table_orders_response[] = $value;
                        }
                    }
                }
            }
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'user_orders' => [],
                'table_orders' => $table_orders_response,
            ]
        ], 200);
    }

    public function selectTable(Request $request)
    {
        config()->set('auth.defaults.guard', 'customer');

        $selectedTable = Table::where('table_number', $request->table_id)->first();

        if (!$selectedTable) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'Table not found.',
                ]
            ], 422);
        }

        // Customer has to leave their current table first to see if they have any unpaid orders on that table
        if ($request->user()->table && $selectedTable != $request->user()->table) {
            return response()->json([
                "success" => false,
                "status" => "leaveCurrentTable",
                "payload" => [
                    'message' => 'Please leave your current Table.',
                ]
            ]);
        }

        // if ($request->user()->table && $selectedTable == $request->user()->table && $request->user()->table_joined_at && $request->user()->table_joined_at->isCurrentHour()) {
        //     if (!$selectedTable->unpaid_orders->count() > 0) {
        //         // dd($selectedTable->orders->where('paid_at' , '>' , now()->subHours(1))->count() > 0);
        //         if (!$selectedTable->orders->where('paid_at', '>', now()->subHours(1))->count() > 0) {
        //             return response()->json([
        //                 "success" => false,
        //                 "status" => "1HourTablePaid",
        //                 "payload" => [
        //                     'message' => 'All orders have been paid within the last 1 hour.',
        //                 ]
        //             ], 422);
        //         }
        //     }
        // }

        // if (!$selectedTable->unpaid_orders->count() > 0) {
        //     // dd($selectedTable->orders->where('paid_at' , '>' , now()->subHours(1))->count() > 0);
        //     if (!$selectedTable->orders->where('paid_at' , '>' , now()->subHours(1))->count() > 0) {
        //         return response()->json([
        //             "success" => false,
        //             "status" => "1HourTablePaid",
        //             "payload" => [
        //                 'message' => 'All orders have been paid within the last 1 hour.',
        //             ]
        //         ], 422);
        //     }

        //     return response()->json([
        //         "success" => true,
        //         "status" => "allOrdersPaid",
        //         "payload" => [
        //             'data' => $selectedTable,
        //         ]
        //     ], 200);
        // }

        // if (!$response->is_fetchable) {
        //     if (in_array($request->user()->id, $response->old_customers)) {
        //         return response()->json([
        //             "success" => false,
        //             "status" => "error",
        //             "message" => "Kick the client."
        //         ], 422);
        //     }
        // }

        // if ($request->user()->id) {
        //     $new_customers = $response->new_customers ?? [];

        //     array_push($new_customers,$request->user()->id);

        //     $new_array = array_unique($new_customers);

        //     $response->new_customers = $new_array;
        //     $response->is_fetchable = 1;
        //     $response->save();
        // }

        $request->user()->update([
            'table_id' => $selectedTable->id,
            'table_joined_at' => now()
        ]);

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $selectedTable,
            ]
        ], 200);
    }

    public function fetchTable(Request $request)
    {
        config()->set('auth.defaults.guard', 'customer');

        $selectedTable = Table::where('id', $request->user()->table_id)->first();

        if (!$selectedTable) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'Table not found.',
                ]
            ], 422);
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $selectedTable,
            ]
        ], 200);
    }

    public function leaveTable(Request $request)
    {
        config()->set('auth.defaults.guard', 'customer');
        // dd($request->user());
        $request->user()->update([
            'table_id' => null,
            'table_joined_at' => null,
        ]);

        return response()->json([
            "success" => true,
            "status" => "success"
        ], 200);
    }

    public function typeform(Request $request)
    {
        try {
            \Log::info(json_encode($request->all()));
        } catch (\Throwable $th) {
            \Log::error($th->getMessage(), $th);
        }

        return response()->json([
            "success" => true,
            "status" => "success"
        ], 200);
    }

    public function toFixed($number, $decimals)
    {
        return number_format($number, $decimals, '.', "");
    }
}
