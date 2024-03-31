<?php

namespace App\Http\Controllers\WaiterAdmin;

use App\Http\Controllers\API\DeliverectController;
use App\Product;
use App\Models\Addon;
use App\Models\Order;
use App\Models\Store;
use App\Models\Table;
use App\Models\WaiterCall;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\OrderDetailAddon;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Controllers\Notification\NotificationController;
use App\Models\Customer;
use Log;

class WaiterController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:waiter');
    }

    public function update_waiter_call_status(WaiterCall $id, Request $request)
    {
        $data = request()->validate([
            'is_completed' => 'required'
        ]);

        if ($id->type == 3) {
            Order::find($id->order->id)->update([
                'status' => 4
            ]);
            // $id->order()->update([
            //     'status' => 4
            // ]);
        }

        if (WaiterCall::whereId($id->id)->update($data)) {

            return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
        }
        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function update_waiter_call_status_order(WaiterCall $id, Request $request)
    {
        $data = request()->validate([
            'is_completed' => 'required'
        ]);

        $waiterCall = WaiterCall::whereId($id->id)->first();

        $order = $waiterCall->order;

        if (!$order) {
            return back()->with(Toastr::error('No order found. ', 'Error'));
        }

        if ($order->paid_amount >= $order->total) {
            return back()->with(Toastr::error('Order is already paid. ', 'Error'));
        }

        $order->update([
            'paid_amount' => $order->total,
        ]);

        WaiterCall::whereId($id->id)->update($data);

        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function update_waiter_call_status_table(WaiterCall $id, Request $request)
    {
        $data = request()->validate([
            'is_completed' => 'required'
        ]);

        $waiterCall = WaiterCall::whereId($id->id)->first();

        $orders = $waiterCall->order->table->unpaid_orders;

        if (!$orders) {
            return back()->with(Toastr::error('No orders found on table. ', 'Error'));
        }

        foreach ($orders as $order) {
            $order->update([
                'paid_amount' => $order->total,
            ]);
        }

        WaiterCall::whereId($id->id)->update($data);

        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function update_order_status(Request $request, Order $order)
    {
        $notification = new NotificationController();

        $data = request()->validate([
            'status' => 'required'
        ]);

        if (Order::whereId($order->id)->update($data)) {
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

        if ($table->unpaid_orders()->update($data)) {
            return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
        }

        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function getProductDetails(Request $request)
    {
        $products_data = Product::with(['addonItems.categories.addons.nested_addons.addon_category.addons', 'time_restrictions', 'discounts.time_restrictions'])->where('store_id', '=', auth()->user()->store_id)
        // $products_data = Product::with(['addonItems.categories.addons', 'time_restrictions', 'discounts.time_restrictions'])->where('store_id', '=', auth()->user()->store_id)
            ->where('is_active', '=', 1)
            ->where('id', '=', $request->product_id)->get();
        $products = [];
        foreach ($products_data as $value) {
            if ($value->time_restrictions->count() > 0) {
                $value['is_availiable'] = $value->get_product_is_availiable();
            }
            if ($value->discounts->count() > 0 && $value->discounts->first()->time_restrictions->count() > 0) {
                $value['is_discountable'] = $value->get_product_discount();
            }
            $products[] = $value;
        }
        return $value;
    }

    public function add_to_cart(Request $request)
    {
        // dump($request->session()->forget('waiter_cart'));
        // dump($request->session()->all());
        // die();

        // $request->session()->forget('waiter_cart');
        // $extra_addon = [];
        // foreach ($request->all() as $key => $value) {
        //     $exp_key = explode('-', $key);
        //     dd($exp_key[0]);
        //     if ($exp_key[0] == 'selectExtra') {
        //         if ($value) {
        //             $extra_addon[] = [
        //                 "id" => $exp_key[1],
        //                 "count" => $value,
        //             ];
        //         }
        //     }
        // }

        $cartItems = [
            "product_id" => $request->select_product,
            "count" => $request->product_count ?? 1,
            "addon" => json_decode($request->addon_list) ?? [],
            // "extra" => $extra_addon,
        ];

        // $sub_total = $this->countSubTotal($cartItems);
        $service_charge = auth()->user()->store->service_charge;
        // $tax = $this->countTax($sub_total);
        // $discount = $this->countDiscount($cartItems);

        $cart = $cartItems;


        $getSessionCart = session()->get('waiter_cart');
        // if cart is empty then this the first product
        if (!$getSessionCart) {
            session()->put('waiter_cart.cart', [$cart]);
        } else {
            session()->push('waiter_cart.cart', $cart);
        }

        $temp = session()->get('waiter_cart');
        $sub_total = 0;
        $tax = 0;
        $discount = 0;
        foreach ($temp["cart"] as $key => $cart) {
            $sub_total = $sub_total + $this->countSubTotal($cart);
            $tax = $tax + $this->countTax($sub_total);
            $discount = $discount + $this->countDiscount($cart);
        }

        // dd($temp);
        // unset($temp["cart"]);
        $temp["store_id"] = auth()->user()->store->view_id;
        $temp["comments"] = "";
        $temp["total"] = $this->countTotal($sub_total, $service_charge, $tax, $discount);
        $temp["store_charge"] = $service_charge;
        $temp["tax"] = $tax;
        $temp["discount"] = $discount;
        $temp["sub_total"] = $sub_total;


        session()->put('waiter_cart', $temp);

        // dd(session()->get('waiter_cart'));

        return back()->with(Toastr::success('Added to cart.', 'Success'));

        // return redirect()->back();
    }

    public function countTotal($sub_total, $service_charge, $tax, $discount)
    {
        return $sub_total + $service_charge + $sub_total * ($tax / 100) - $discount;
    }

    public function countDiscount(array $cart)
    {
        $product = Product::findOrFail($cart['product_id']);

        if ($product->discounts->count() > 0) {
            $discount = $product->discounts->first();

            if ($discount->discount_type == 1) {
                return $discount->discount_price_fixed * $cart['count'];
            } else if ($discount->discount_type == 2) {
                return ($product->price / 100) * $discount->discount_price_percentage * $cart['count'];
            }
        }

        // dd($product->discounts->first());
    }

    public function countTax($sub_total)
    {
        return ($sub_total * auth()->user()->store->tax) / 100;
    }

    public function countSubTotal(array $cart)
    {
        $sum = 0;
        $product = Product::findOrFail($cart['product_id']);

        $sum = $sum + $product->price;

        if ($cart['addon'] != null) {
            foreach ($cart['addon'] as $key=>$addon_id) {
                // dump($addon_id);
                if ($tempAddon = Addon::find($key)) {
                    // dump("addon => ".$tempAddon->price);
                    $sum = $sum + $tempAddon->price;
                    if(isset($addon_id->nested_addons)){
                        foreach ($addon_id->nested_addons as $key=> $nested_addon_count) {
                            $tempNestedAddon = Addon::find($key);
                            $sum = $sum + $tempNestedAddon->price * $nested_addon_count;
                            // dump("addon => ".$tempNestedAddon->price.' * '. $nested_addon_count .' = '. $tempNestedAddon->price*$nested_addon_count);
                        }
                    }
                    // dump("addon sum =>".$sum);
                }
            }
        }

        $sum = $sum * $cart['count'];
        return $sum;
    }

    public function create_order(Request $request)
    {
        // dd(session()->get('waiter_cart'));
        $data = session()->get('waiter_cart');
        $orderItems = session()->get('waiter_cart')['cart'];
        unset($data['cart']);
        $store = Store::all()->where('view_id', '=', session()->get('waiter_cart')['store_id'])->first();

        $data['store_id'] = $store->id;
        $data['order_unique_id'] = "ODR-" . time();

        if ($customer = Customer::find($request->add_customer)) {
            $data['customer_name'] = $customer->first_name. ' '.$customer->first_name;
            $data['customer_phone'] = $customer->phone;
            $data['customer_id'] = $customer->id;
        } else {
            $data['customer_name'] = null;
            $data['customer_phone'] = null;
            $data['customer_id'] = null;
        }


        if (Table::find($request->select_table)) {
            $data['table_no'] = $request->select_table;
        } else {
            $data['table_no'] = $store->tables()->inRandomOrder()->first() ? $store->tables()->inRandomOrder()->first()->id : null;
        }

        $new_order = Order::create($data);
        $new_order['status'] = 1;
        $notification = new NotificationController();

        if ($new_order) {
            $order_id = Order::all()->where('order_unique_id', '=', $data['order_unique_id'])->first()['id'];

            auth()->user()->waiter_orders()->create([
                'order_id' => $order_id
            ]);

            $items = array();
            foreach ($orderItems as $value) {

                // return response()->json([
                //     "value" => $value,
                //     "addon" => $value['addon'],
                //     "true_false" => isset($value['addon']),
                // ], 422);

                $temp = [];
                $temp['order_id'] = $order_id;
                $product = Product::all()->where('id', '=', $value['product_id'])->first();

                // if ($value['addon'] == null) {

                $temp['name'] = $product['name'];
                $temp['price'] = $product['price'];
                $temp['kitchen_location_id'] = $product->kitchen_location_id;
                $temp['sku'] = $product->sku;

                // }
                // else {
                //     foreach ($value['addon'] as $key => $addon_id) {
                //         $addon = Addon::find($addon_id);
                //         $temp['name'] = $product['name'] . "-" . $addon->addon_name;
                //         $temp['price'] = $product['price'] + $addon->price;
                //         $temp['kitchen_location_id'] = $addon->kitchen_location_id;
                //     }
                // }

                $temp['quantity'] = $value['count'];
                $temp['status'] = 0;

                $orderDetail = OrderDetails::create($temp);

                if (isset($value['addon'])) {
                    $value['addon'] = (array)$value['addon'];
                    $temp = array();
                    foreach ($value['addon'] as $key => $addon_id) {
                        $addon_id = (array)$addon_id;
                        $addon = Addon::find($key);
                        $temp['order_detail_id'] = $orderDetail->id;
                        $temp['addon_id'] = $addon->id;
                        $temp['addon_name'] = $addon->addon_name;
                        $temp['addon_price'] = $addon->price;
                        $temp['addon_count'] = $addon_id['count'] ?? 1;
                        $temp['kitchen_location_id'] = $addon->kitchen_location_id;
                        $temp['status'] = 0;
                        $temp['sku'] = $addon->sku;
                        $curAddon = OrderDetailAddon::create($temp);

                        if (isset($addon_id['nested_addons']) && !empty($addon_id['nested_addons'])) {
                            $nested_temp = array();
                            foreach ($addon_id['nested_addons'] as $key => $nested_addons) {
                                $nested_addon = Addon::find($key);
                                $nested_temp['parent_addon_id'] = $curAddon->id;
                                $nested_temp['order_detail_id'] = $orderDetail->id;
                                $nested_temp['kitchen_location_id'] = $nested_addon->kitchen_location_id;
                                $nested_temp['status'] = 0;
                                $nested_temp['addon_id'] = $nested_addon->id;
                                $nested_temp['addon_name'] = $nested_addon->addon_name;
                                $nested_temp['addon_price'] = $nested_addon->price;
                                $nested_temp['addon_count'] = $nested_addons ?? 1;
                                $nested_temp['sku'] = $nested_addon->sku;
                                OrderDetailAddon::create($nested_temp);
                            }
                        }
                    }
                }
            }

            $unpaid_orders = $new_order->table->kitchen_orders;

            if ($unpaid_orders->count() > 0) {
                $group_id = $unpaid_orders->first()->order_group_id ? $unpaid_orders->first()->order_group_id : "ODRGRP" . time();

                $new_order->order_group_id = $group_id;
                $new_order->save();
            } else {
                $group_id = "ODRGRP" . time();
                $new_order->order_group_id = $group_id;
                $new_order->save();
            }

            $new_order->status = 2;
            $new_order->save();

            $response_data = Order::all()->where('customer_phone', '=', null);

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
            $notification->WhatsAppOrderNotification(Order::with('orderDetails.OrderDetailsExtraAddon')->where('id', $new_order->id)->get()->toArray());

            // return response()->json([
            //     "success" => true,
            //     "status" => "success",
            //     "payload" => [
            //         'user_orders' => $response
            //     ]
            // ], 200);
        }

        $request->session()->forget('waiter_cart');
        return back()->with(Toastr::success('Order created successfully.', 'Success'));
    }

    public function getCustomerDetails(Request $request)
    {
        $value = $request->value;

        $customers = Customer::whereLike(['first_name','last_name', 'email', 'phone'], $value)->get();

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'customers' => $customers
            ]
        ], 200);

        // return $customers;
    }

    public function fetchTableOrderUsers(Request $request)
    {
        $table_orders = null;
        $table_orders_response = [];

        if ($request->table_no != null) {
            $last_table = Table::find($request->table_no);

            if ($last_table) {
                if ($last_table->unpaid_orders->count() > 0) {
                    $last_table_order = $last_table->orders->last();

                    if ($last_table_order) {
                        $table_orders = Order::with('orderDetails.OrderDetailsExtraAddon')->where('order_group_id', '=', $last_table_order->order_group_id)->get()->sortByDesc('id');

                        foreach ($table_orders as $value) {
                            $customers[] = $value->customer;
                        }
                    }
                }
                else{
                    $customers = Customer::all()->sortByDesc('id');
                }
            }
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'customers' => $customers
            ]
        ], 200);
    }
}
