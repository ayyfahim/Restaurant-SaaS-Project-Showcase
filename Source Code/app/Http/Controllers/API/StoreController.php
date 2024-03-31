<?php

namespace App\Http\Controllers\API;

use App\Application;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\OrderRating;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Review;

class StoreController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function view(Request $request)
    {
        $data = Store::all()->where('id', "=", $request->shopId)->count();

        if ($data > 0) {
            $data = Store::all()->where('id', "=", $request->shopId);
            $response = array();
            foreach ($data as $key)
                $response   = $key;

            $response['product_count'] = Product::all()->where('store_id', '=', $request->shopId)->count();
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
                    "type" => "data not found (ERROR:RT404)",
                    "message" => "We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }
    }
    public function update(Request $request)
    {
        $data = $request->all();
        $shopId = $data['shopId'];
        unset($data['shopId']);
        if ($request->password != NULL) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (Store::whereId($shopId)->update($data)) {
            $response = Store::all()->where('id', "=", $shopId);
            foreach ($response as $key)
                $response  = $key;
            $response['product_count'] = Product::all()->where('store_id', '=', $request->shopId)->count();
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
    public function orders_view(Request $request)
    {
        $data = $request->all();
        $shopId = $data['shopId'];
        $Order_data = Order::all()->SortByDesc('id')->where('store_id', '=', $request->shopId);
        $response = array();
        foreach ($Order_data  as $data) {
            $response[] = $data;
        }
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $response,

            ]
        ], 200);
    }
    public function orders_view_details(Request $request)
    {
        $data = $request->all();
        $orderDetails = Order::with('orderDetails.OrderDetailsExtraAddon')->where('id', $data['order_id'])->get()->first();
        $response = $orderDetails;

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $response,

            ]
        ], 200);
    }
    public function update_order_status(Request $request)
    {
        $data = $request->all();
        $order_id = $data['order_id'];
        unset($data['order_id']);

        Order::whereId($order_id)->update($data);

        $shopId = $data['store_id'];
        $Order_data = Order::all()->SortByDesc('id')->where('store_id', '=', $request->shopId);
        $response = array();
        foreach ($Order_data  as $data) {
            $response[] = $data;
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $response,
            ]
        ], 200);
    }

    public function orederRating(Request $request)
    {
        try {
            $order = Order::find($request->order_id);
            $order_rating = new OrderRating();

            $order_rating->order_id = $request->order_id;
            $order_rating->reason = $request->reason;
            $order_rating->rating = $request->rating;
            $order_rating->comment = $request->comment;
            $order_rating->order_type = $request->subject;

            $order_rating->save();

            $deliverect = new DeliverectController();
            $deliverect->updateOrderRating($order->id, [
                "orderId" => $order->order_unique_id,
                "orderDate" => $order->created_at,
                "rating" => [
                    "reason"=> 10000,
                    "rating"=> 5,
                    "comment"=> "Food was amazing.",
                    "subject"=> 1
                ],
            ]);

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    "message" => "Thank You For Your Feedback!"
                ]
            ], 200);

        } catch (\Throwable $th) {
            $response =  response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    "message" => $th
                ]
            ], 400);
        }
    }
}
