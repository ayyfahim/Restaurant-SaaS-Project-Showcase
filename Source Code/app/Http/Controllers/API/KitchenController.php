<?php

namespace App\Http\Controllers\API;

use App\Kitchen;
use App\Models\Order;
use App\Models\Table;
use App\Models\WaiterCall;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\OrderDetailAddon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Notification\NotificationController;

class KitchenController extends Controller
{
    public function all_kitchen(Request $request)
    {
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => Kitchen::all()->sortByDesc('id')->where('store_id', '=', auth()->id()),
            ]
        ], 200);
    }
    public function add_kitchen(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'password' => 'required',
        //     'store_id' => '',
        //     'phone' => '',
        // ]);
        $data = $request->all();
        $data['is_main'] = (bool) $request->is_main;
        $data['store_id'] = auth()->id();
        $data['password'] = Hash::make($request->password);
        if (Kitchen::create($data)) {
            $data = Kitchen::all()->where('store_id', "=", auth()->id())->sortByDesc('id');
            $response = array();
            foreach ($data as $key) {
                $response[] = $key;
            }

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response,
                ]
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "status" => "error",
                "error" => [
                    "code" => 400,
                    "type" => "Bad Request (ERROR:RT404)",
                    "message" => "We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }
    }

    public function edit_kitchens(Kitchen $kitchen, Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'phone' => '',
        // ]);
        // $data['is_main'] = (bool) $request->is_main;
        if ($kitchen->update($request->all())) {
            $data = Kitchen::all()->where('store_id', "=", auth()->id())->sortByDesc('id');
            $response = array();
            foreach ($data as $key) {
                $response[] = $key;
            }

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response,
                ]
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "status" => "error",
                "error" => [
                    "code" => 400,
                    "type" => "Bad Request (ERROR:RT404)",
                    "message" => "We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }
    }

    public function delete_kitchens(Kitchen $kitchen, Request $request)
    {
        if ($kitchen->delete()) {
            $data = Kitchen::all()->where('store_id', "=", auth()->id())->sortByDesc('id');
            $response = array();
            foreach ($data as $key) {
                $response[] = $key;
            }

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response,
                ]
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "status" => "error",
                "error" => [
                    "code" => 400,
                    "type" => "Bad Request (ERROR:RT404)",
                    "message" => "We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }
    }

    public function mainKitchenOrders(Request $request)
    {
        if (!$request->user()->is_main) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "error" => [
                    "code" => 400,
                    "type" => "Bad Request (ERROR:RT404)",
                    "message" => "The kitchen is not main kitchen."
                ],
            ], 400);
        }

        $tables = Table::with('kitchen_orders.orderDetails.OrderDetailsExtraAddon')->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->values()->toArray();
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->user()->store_id)->count();

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'tables' => $tables,
                'orders_count' => $orders_count,
            ]
        ], 200);
    }

    public function kitchenOrders(Request $request)
    {
        $id = $request->user()->id;
        $kitchenLocation = Kitchen::findOrFail($id);
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->user()->store_id)->count();

        $tables = Table::with([
            'kitchen_orders.orderDetails.OrderDetailsExtraAddon' => function ($query) use ($id) {
                $query->where('kitchen_location_id', $id);
                $query->where('status', 0);
            },
        ])->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->values()->toArray();

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
                }
            }
        }

        foreach ($tables as $table_key => $table) {
            foreach ($table['kitchen_orders'] as $order_key => $order) {
                foreach ($order['order_details'] as $order_detail_key => $detail) {
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

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'tables' => $tables,
                'orders_count' => $orders_count,
                'kitchenLocation' => $kitchenLocation,
            ]
        ], 200);
    }

    public function allKitchenOrders(Request $request)
    {
        $main_kitchen_orders = Table::with('kitchen_orders.orderDetails.OrderDetailsExtraAddon')->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->values()->toArray();
        $orders_count = Order::all()->SortByDesc('id')->where('store_id', auth()->user()->store_id)->count();

        $id = $request->user()->id;
        $kitchenLocation = Kitchen::findOrFail($id);
        $tables = Table::with([
            'kitchen_orders.orderDetails.OrderDetailsExtraAddon' => function ($query) use ($id) {
                $query->where('kitchen_location_id', $id);
                $query->where('status', 0);
            },
        ])->where('store_id', auth()->user()->store_id)->get()->SortByDesc('id')->values()->toArray();

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
                }
            }
        }

        foreach ($tables as $table_key => $table) {
            foreach ($table['kitchen_orders'] as $order_key => $order) {
                foreach ($order['order_details'] as $order_detail_key => $detail) {
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

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'main_kitchen_orders' => $main_kitchen_orders,
                'only_kitchen_orders' => $tables,
                // 'orders_count' => $orders_count,
                'kitchenLocation' => $kitchenLocation,
            ]
        ], 200);
    }

    public function update_order_status_changables(Request $request, Order $order)
    {
        foreach ($request->datas as $key => $data) {
            if ($data['type'] == "order_detail") {
                OrderDetails::find($data['id'])->update([
                    'status' => 1,
                ]);
            } elseif ($data['type'] == "order_details_extra_addon") {
                OrderDetailAddon::find($data['id'])->update([
                    'status' => 1,
                ]);
            }
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                "message" => "Successfully updated."
            ]
        ], 200);
    }

    public function update_order_status(Request $request, Order $order)
    {
        $notification = new NotificationController();

        $data = $request->all();

        if (Order::find($order->id)->update($data)) {
            if ($request->status == 5) {
                $order = Order::find($order->id);
                $title = "Waiter Call";
                $body = $order['table_no'] != null ? "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}."
                    : "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}.";
                try {
                    $notification->send_notification($title, $body, $order['store_id']);
                } catch (\Exception $e) {
                }
                $data['customer_name'] = $order['customer_name'];
                $data['customer_phone'] = $order['customer_phone'];
                $data['table_name'] = $order['table_no'];
                $data['comment'] = $body;
                $data['store_id'] = $order['store_id'];
                $data['type'] = 3;
                $data['order_id'] = $order['id'];
                unset($data['status']);
                WaiterCall::create($data);
            }

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    "message" => "Successfully updated."
                ]
            ], 200);
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                "message" => "Successfully updated."
            ]
        ], 200);
    }

    public function update_table_status(Request $request, Table $table)
    {
        $notification = new NotificationController();

        $data = $request->all();

        $waiter_call = [];

        $table->load('kitchen_orders');

        try {
            foreach ($table->kitchen_orders as $order) {
                Order::find($order->id)->update($data);

                if ($request->status == 5) {
                    $title = "Waiter Call";
                    $body = $order['table_no'] != null ? "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}."
                        : "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}.";
                    try {
                        $notification->send_notification($title, $body, $order['store_id']);
                    } catch (\Exception $e) {
                    }
                    $waiter_call['customer_name'] = $order['customer_name'];
                    $waiter_call['customer_phone'] = $order['customer_phone'];
                    $waiter_call['table_name'] = $order['table_no'];
                    $waiter_call['comment'] = $body;
                    $waiter_call['store_id'] = $order['store_id'];
                    $waiter_call['type'] = 3;
                    $waiter_call['order_id'] = $order['id'];
                    WaiterCall::create($waiter_call);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    "message" => $th
                ]
            ], 400);
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                "message" => "Successfully updated."
            ]
        ], 200);
    }
}
