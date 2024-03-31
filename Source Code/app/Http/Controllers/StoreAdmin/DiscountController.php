<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Product;
use App\Discount;
use App\TimeRestriction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function adddiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_name' => 'required',
            'discount_description' => 'required',
            'discount_type' => 'required|integer',
            'discount_price_fixed' => Rule::requiredIf($request->discount_type == 1),
            'discount_price_percentage' => Rule::requiredIf($request->discount_type == 2),
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $discount = Discount::create([
            'discount_name' => $request->discount_name,
            'discount_description' => $request->discount_description,
            'discount_type' => $request->discount_type,
            'discount_price_fixed' => $request->discount_price_fixed,
            'discount_price_percentage' => $request->discount_price_percentage,
            'is_active' => $request->is_active == "On" ? true : false,
            'store_id' => auth()->id(),
        ]);

        if ($request->time_restriction) {
            $time = TimeRestriction::findOrFail($request->time_restriction);
            $discount->time_restrictions()->save($time);
        } else {
            $discount->time_restrictions()->wherePivot('restrictionable_id', '=', $discount->id)->detach();
        }

        foreach ($request->selected_product_ids as $product_id) {
            $product = Product::findOrFail($product_id);
            $product->discounts()->attach($discount);
        }

        return Redirect::route("store_admin.discount")->with(Toastr::success('Discount added successfully. ', 'Success'));
    }

    public function updatediscount(Request $request, $id)
    {
        // dd($request->is_active == "on");
        $discount = Discount::findOrFail($id);
        // dd(!$discount->time_restrictions()->wherePivot('restrictionable_id', '=', $discount->id)->count() > 0);

        $validator = Validator::make($request->all(), [
            'discount_name' => 'required',
            'discount_description' => 'required',
            'discount_type' => 'required|integer',
            'discount_price_fixed' => Rule::requiredIf($request->discount_type == 1),
            'discount_price_percentage' => Rule::requiredIf($request->discount_type == 2),
            // 'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $discount->update([
            'discount_name' => $request->discount_name,
            'discount_description' => $request->discount_description,
            'discount_type' => $request->discount_type,
            'discount_price_fixed' => $request->discount_price_fixed,
            'discount_price_percentage' => $request->discount_price_percentage,
            'is_active' => $request->is_active == "on" ? true : false,
        ]);


        if ($request->time_restriction) {
            $discount->time_restrictions()->wherePivot('restrictionable_id', '=', $discount->id)->detach();
            $time = TimeRestriction::findOrFail($request->time_restriction);
            $discount->time_restrictions()->save($time);
        } else {
            $discount->time_restrictions()->wherePivot('restrictionable_id', '=', $discount->id)->detach();
        }

        $discount->products()->sync($request->selected_product_ids);
        $discount->save();

        return Redirect::route("store_admin.discount")->with(Toastr::success('Discount updated successfully. ', 'Success'));
    }

    public function deletediscount(Discount $discount)
    {
        $discount->time_restrictions()->wherePivot('restrictionable_id', '=', $discount->id)->detach();
        $discount->products()->detach();

        if ($discount->delete())
            return Redirect::route("store_admin.discount")->with(Toastr::success('Discount Deleted successfully ', 'Success'));
    }
}
