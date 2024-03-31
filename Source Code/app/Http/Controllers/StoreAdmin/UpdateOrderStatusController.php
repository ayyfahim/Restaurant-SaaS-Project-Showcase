<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Http\Controllers\API\DeliverectController;
use App\Models\Order;
use App\Models\WaiterCall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Notification\NotificationController;
use Log;

class UpdateOrderStatusController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }
    public function updateStatus(Request $request, $id)
    {
        $notification = new NotificationController();

        $data = request()->validate([
            'status' => 'required'
        ]);

        if ($order = Order::where('order_unique_id', $id)->first()) {
            $order->update($data);
            ////            $notification->WhatsAppOrderNotification($order);
            if ($request->status == 2) {
                // $order = Order::find($id);
                $get_new_order = Order::with('orderDetails.OrderDetailsExtraAddon')->where('order_unique_id', $id)->get();
                if ($get_new_order->first()->status == '2') {
                    try {
                        $deliverectController = new  DeliverectController();
                        $deliverectController->createDeliverectOrder($get_new_order->first());
                        // $this->createDeliverectOrder($get_new_order->first());
                    } catch (\Throwable $e) {
                        Log::error($e, ["store_id" => $order->store_id, "order_id" => $get_new_order->first()->id]);
                    }
                }
                $title = "Waiter Call";
                // $body = $order['table_no'] != NULL ? "{$order['customer_name']} ({$order['order_unique_id']}) - order has been accepted and being prepared for Table #{$order['table_no']}."
                //     : "{$order['customer_name']} ({$order['order_unique_id']}) - order has been accepted and being prepared for Table #{$order['table_no']}.";
                $body = $order['table_no'] != NULL ? "Order has been accepted and being prepared for Table #{$order['table_no']}."
                    : "Order has been accepted and being prepared for Table #{$order['table_no']}.";
                try {
                    $notification->send_notification($title, $body, $order['store_id']);
                } catch (\Exception $e) {
                }
                $data['customer_name'] = $order['customer_name'];
                $data['customer_phone'] = $order['customer_phone'];
                $data['table_name'] = $order['table_no'];
                $data['comment'] = $body;
                $data['store_id'] = $order['store_id'];
                $data['type'] = 2;
                $data['order_id'] = $order['id'];
                unset($data['status']);
                WaiterCall::create($data);
            }
        }
        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }
}
