<?php

namespace App\Http\Controllers\KitchenAdmin;

use App\Models\Order;
use App\Models\Table;
use App\Models\WaiterCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Controllers\Notification\NotificationController;
use App\Models\OrderDetailAddon;
use App\Models\OrderDetails;

class KitchenController  extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:kitchen');
    }

    public function update_order_status_changables(Request $request, Order $order)
    {
        $notification = new NotificationController();

        // dd($request->all());

        foreach ($request->datas as $key => $data) {
            // dd($data['type']);
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

        // if ($request->status == 5) {
        //     $order = Order::find($order->id);
        //     $title = "Waiter Call";
        //     $body = $order['table_no'] != NULL ? "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}."
        //         : "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}.";
        //     try {
        //         $notification->send_notification($title, $body, $order['store_id']);
        //     } catch (\Exception $e) {
        //     }
        //     $data['customer_name'] = $order['customer_name'];
        //     $data['customer_phone'] = $order['customer_phone'];
        //     $data['table_name'] = $order['table_no'];
        //     $data['comment'] = $body;
        //     $data['store_id'] = $order['store_id'];
        //     $data['type'] = 3;
        //     $data['order_id'] = $order['id'];
        //     unset($data['status']);
        //     WaiterCall::create($data);
        // }

        // return back()->with(Toastr::success('Status Updated successfully ', 'Success'));


        // return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function update_order_status(Request $request, Order $order)
    {
        $notification = new NotificationController();

        $data = request()->validate([
            'status' => 'required'
        ]);

        if (Order::find($order->id)->update($data)) {
            if ($request->status == 5) {
                $order = Order::find($order->id);
                $title = "Waiter Call";
                // $body = $order['table_no'] != null ? "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}."
                //     : "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}.";
                $body = $order['table_no'] != null ? "Table #{$order->table->table_number} is ready to serve."
                    : "Table #{$order->table->table_number} is ready to serve.";
                try {
                    $notification->send_notification($title, $body, $order['store_id']);
                } catch (\Exception $e) {
                }
                $data['customer_name'] = $order['customer_name'];
                $data['customer_phone'] = $order['customer_phone'];
                $data['table_name'] = $order->table->table_number;
                $data['comment'] = $body;
                $data['store_id'] = $order['store_id'];
                $data['type'] = 3;
                $data['order_id'] = $order['id'];
                unset($data['status']);
                WaiterCall::create($data);
            }
            return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
        }
        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function update_table_status(Request $request, Table $table)
    {
        $notification = new NotificationController();

        $data = request()->validate([
            'status' => 'required'
        ]);
        $waiter_call = [];

        $table->load('kitchen_orders');

        // dd($table);

        try {
            foreach ($table->kitchen_orders as $order) {
                // DB::beginTransaction();
                // $data['served_at'] = now();
                // $order->update($data);
                Order::find($order->id)->update($data);

                if ($request->status == 5) {
                    // $order = Order::find($order->id);
                    $title = "Waiter Call";
                    // $body = $order['table_no'] != null ? "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}."
                    //     : "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order['table_no']}.";
                    $body = $order['table_no'] != null ? "Table #{$order->table->table_number} is ready to serve."
                        : "Table #{$order->table->table_number} is ready to serve.";
                    try {
                        $notification->send_notification($title, $body, $order['store_id']);
                    } catch (\Exception $e) {
                    }
                    $waiter_call['customer_name'] = $order['customer_name'];
                    $waiter_call['customer_phone'] = $order['customer_phone'];
                    $waiter_call['table_name'] = $order->table->table_number;
                    $waiter_call['comment'] = $body;
                    $waiter_call['store_id'] = $order['store_id'];
                    $waiter_call['type'] = 3;
                    $waiter_call['order_id'] = $order['id'];
                    WaiterCall::create($waiter_call);
                }
                // DB::commit();
            }
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
            return back()->with(Toastr::error('An error occured. ', 'Error'));
        }
        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function update_table_status_2(Request $request, Table $table)
    {
        $notification = new NotificationController();

        $data = request()->validate([
            'status' => 'required'
        ]);

        $table->load('kitchen_orders');

        // try {
        //     DB::beginTransaction();
        foreach ($table->kitchen_orders as $order) {
            try {
                DB::beginTransaction();
                $order->update($data);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }

            if ($request->status == 5) {
                // $order = Order::find($order->id);
                try {
                    DB::beginTransaction();
                    $title = "Waiter Call";
                    $body = $order['table_no'] != null ? "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order->table->table_number}."
                        : "{$order['customer_name']} ({$order['order_unique_id']}) - order ready to serve Table #{$order->table->table_number}.";
                    try {
                        $notification->send_notification($title, $body, $order['store_id']);
                    } catch (\Exception $e) {
                    }
                    $data['customer_name'] = $order['customer_name'];
                    $data['customer_phone'] = $order['customer_phone'];
                    $data['table_name'] = $order->table->table_number;
                    $data['comment'] = $body;
                    $data['store_id'] = $order['store_id'];
                    $data['type'] = 3;
                    $data['order_id'] = $order['id'];
                    unset($data['status']);
                    WaiterCall::create($data);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                }
            }
        }
        //     DB::commit();
        // } catch (\Throwable $th) {
        //     // DB::rollBack();
        //     throw $th;
        //     return back()->with(Toastr::error('An error occured. ', 'Error'));
        // }

        // if ($table->unpaid_orders()->update($data)) {
        //     return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
        // }

        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }
}
