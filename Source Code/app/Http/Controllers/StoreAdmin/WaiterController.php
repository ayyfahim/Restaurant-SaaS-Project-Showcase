<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Waiter;
use App\Models\WaiterCall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Table;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class WaiterController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function add_waiter(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:waiters',
            'password' => 'required',
            'store_id' => '',
            'phone' => '',
        ]);
        $data['store_id'] = auth()->id();
        $data['password'] = Hash::make($request->password);
        if (Waiter::create($data))
            return Redirect::route("store_admin.all_waiters")->with(Toastr::success('Waiter Added successfully ', 'Success'));
    }

    public function edit_waiter(Waiter $waiter, Request $request)
    {
        if ($waiter->update($request->all()))
            return Redirect::route("store_admin.editwaiters", $waiter->id)->with(Toastr::success('Waiter Updated successfully ', 'Success'));
    }

    public function set_water_to_table(Request $request)
    {
        // dd($request->all());
        // $waiter = Waiter::findOrFail($request->waiter_id);
        $table = Table::findOrFail($request->table_id);

        if ($table->waiters()->sync($request->waiter_ids)) {
            $table->save();
            return Redirect::route("store_admin.all_tables")->with(Toastr::success('Waiter added to the table successfully!', 'Success'));
        }
    }

    public function delete_waiter(Waiter $waiter, Request $request)
    {
        if ($waiter->delete())
            return Redirect::route("store_admin.all_waiters")->with(Toastr::success('Waiter Deleted successfully ', 'Success'));
    }

    public function update_waiter_call_status(WaiterCall $id, Request $request)
    {
        $data = request()->validate([
            'is_completed' => 'required'
        ]);
        if (WaiterCall::whereId($id->id)->update($data)) {

            return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
        }
        return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }
}
