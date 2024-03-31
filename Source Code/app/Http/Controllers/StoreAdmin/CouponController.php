<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function addcoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required',
            'coupon_amount' => 'required|numeric',
            'coupon_minimum_spend' => 'nullable|numeric',
            'coupon_maximum_spend' => 'nullable|numeric',
            'coupon_epiration' => 'required',
            'accepted_products' => '',
            'excluded_products' => '',
            'accepted_categories' => '',
            'excluded_categories' => '',
            'limit_per_user' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        if (array_intersect($request->accepted_products ?? [], $request->excluded_products ?? [])) {
            return Redirect::back()->with(Toastr::error('Selected products and Excluded products can\'t be the same. ', 'Error'));
        } else if (array_intersect($request->accepted_categories ?? [], $request->excluded_categories ?? [])) {
            return Redirect::back()->with(Toastr::error('Selected categories and Excluded categories can\'t be the same. ', 'Error'));
        }

        $coupon = Coupon::create([
            'code' => $request->coupon_code,
            'percantage_amount' => null,
            'fixed_amount' => $request->coupon_amount,
            'minimum_spend' => $request->coupon_minimum_spend ??null,
            'maximum_spend' => $request->coupon_maximum_spend ??null,
            'accepted_products' => $request->accepted_products ?? null,
            'excluded_products' => $request->excluded_products ?? null,
            'accepted_categories' => $request->accepted_categories ?? null,
            'excluded_categories' => $request->excluded_categories ?? null,
            'expires_at' => Carbon::createFromFormat('Y-m-d', $request->coupon_epiration),
            'limit_per_user' => $request->limit_per_user,
            'store_id' => auth()->id()
        ]);

        return Redirect::route("store_admin.coupon")->with(Toastr::success('Coupon added successfully. ', 'Success'));
    }

    public function updatecoupon(Coupon $coupon, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required',
            'coupon_amount' => 'required|numeric',
            'coupon_minimum_spend' => 'nullable|numeric',
            'coupon_maximum_spend' => 'nullable|numeric',
            'coupon_epiration' => 'required',
            'accepted_products' => '',
            'excluded_products' => '',
            'accepted_categories' => '',
            'excluded_categories' => '',
            'limit_per_user' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        if (array_intersect($request->accepted_products ?? [], $request->excluded_products ?? [])) {
            return Redirect::back()->with(Toastr::error('Selected products and Excluded products can\'t be the same. ', 'Error'));
        } else if (array_intersect($request->accepted_categories ?? [], $request->excluded_categories ?? [])) {
            return Redirect::back()->with(Toastr::error('Selected categories and Excluded categories can\'t be the same. ', 'Error'));
        }

        $coupon->update([
            'code' => $request->coupon_code,
            'percantage_amount' => null,
            'fixed_amount' => $request->coupon_amount,
            'minimum_spend' => $request->coupon_minimum_spend ??null,
            'maximum_spend' => $request->coupon_maximum_spend ??null,
            'accepted_products' => $request->accepted_products ?? null,
            'excluded_products' => $request->excluded_products ?? null,
            'accepted_categories' => $request->accepted_categories ?? null,
            'excluded_categories' => $request->excluded_categories ?? null,
            'expires_at' => Carbon::createFromFormat('Y-m-d', $request->coupon_epiration),
            'limit_per_user' => $request->limit_per_user,
        ]);

        return Redirect::route("store_admin.coupon", $coupon->id)->with(Toastr::success('Coupon updated successfully. ', 'Success'));
    }

    public function deletecoupon(Coupon $coupon)
    {
        if ($coupon->delete())
            return Redirect::route("store_admin.coupon")->with(Toastr::success('Coupon Deleted successfully ', 'Success'));
    }
}
