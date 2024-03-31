<?php

namespace App\Http\Controllers\WEBAPI;

use App\Coupon;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:customer');
    }

    public function check_coupon(Request $request)
    {
        $store = Store::whereViewId($request->storeId)->firstOrFail();

        if (!$store) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'Store not found.',
                ]
            ], 422);
        }

        $coupon = Coupon::where('code', $request->code)->where('store_id', $store->id)->first();

        if (!$coupon) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'Coupon not found.',
                ]
            ], 422);
        }

        if ($coupon->expires_at->isPast()) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'Coupon expired.',
                ]
            ], 422);
        }

        if ($request->user()->coupons->where('id', $coupon->id)->count() >= $coupon->limit_per_user) {
            return response()->json([
                "success" => false,
                "status" => "failed",
                "payload" => [
                    'message' => 'You have exceeded the limit for this coupon.',
                ]
            ], 422);
        }

        if(!$coupon->accepted_categories)
            $coupon->accepted_categories = [];

        if(!$coupon->accepted_products)
            $coupon->accepted_products = [];

        if(!$coupon->excluded_categories)
            $coupon->excluded_categories = [];

        if(!$coupon->excluded_products)
            $coupon->excluded_products = [];

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'coupon' => $coupon,
                'user_coupons' => $request->user()->coupons->where('id', $coupon->id)->count(),
            ]
        ], 200);
    }
}
