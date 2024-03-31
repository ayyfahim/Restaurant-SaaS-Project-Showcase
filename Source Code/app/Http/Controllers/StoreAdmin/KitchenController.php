<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Waiter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Kitchen;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class KitchenController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function add_kitchen(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'store_id' => '',
            'phone' => '',
        ]);
        $data['is_main'] = (bool) $request->is_main;
        $data['store_id'] = auth()->id();
        $data['password'] = Hash::make($request->password);
        if (Kitchen::whereStoreId(auth()->id())->whereIsMain(1)->first() && $data['is_main']) {
            return back()->with(Toastr::error('You can only have one main kitchen. ', 'error'));
        }

        if (Kitchen::create($data))
            return Redirect::route("store_admin.all_kitchens")->with(Toastr::success('Kitchen Added successfully ', 'Success'));
    }

    public function edit_kitchens(Kitchen $kitchen, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => '',
        ]);
        $data['is_main'] = (bool) $request->is_main;
        if ($kitchen->update($data))
            return Redirect::route("store_admin.editkitchens", $kitchen->id)->with(Toastr::success('Kitchen Updated successfully ', 'Success'));
    }

    public function update_password(Kitchen $kitchen, Request $request)
    {
        $data = $request->validate([
            'newPassword' => 'required',
            'reNewPassword' => 'required|same:newPassword',
        ]);
        $password = Hash::make($request->newPassword);
        if ($kitchen->update(['password' => $password]))
            return Redirect::route("store_admin.changePassword", $kitchen->id)->with(Toastr::success('Kitchen Updated successfully ', 'Success'));
    }

    public function delete_kitchens(Kitchen $kitchen, Request $request)
    {
        if ($kitchen->delete())
            return Redirect::route("store_admin.all_kitchens")->with(Toastr::success('Kitchen Deleted successfully ', 'Success'));
    }
}
