<?php

namespace App\Http\Controllers\API;

use App\Waiter;
use App\Models\Table;
use App\Models\Setting;
use App\Models\WaiterCall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class WaiterController extends Controller
{
    // public function  __construct()
    // {
    //     $this->middleware('auth:store');
    // }

    public function all_waiter()
    {
        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => Waiter::with('store_tables')->get()->sortByDesc('id')->where('store_id', '=', auth()->id()),
            ]
        ], 200);
    }

    public function add_waiter(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'password' => 'required',
        //     'store_id' => '',
        //     'phone' => '',
        // ]);
        $data = $request->all();
        $data['store_id'] = auth()->id();
        $data['password'] = Hash::make($request->password);
        if (Waiter::create($data)) {
            $data = Waiter::all()->where('store_id', "=", auth()->id())->sortByDesc('id');
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

    public function edit_waiter(Waiter $waiter, Request $request)
    {
        if ($waiter->update($request->all())) {
            $data = Waiter::all()->where('store_id', "=", auth()->id())->sortByDesc('id');
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
        if ($waiter->delete()) {
            $data = Waiter::all()->where('store_id', "=", auth()->id())->sortByDesc('id');
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

    public function waiter_calls()
    {
        // also table id
        $calls = auth()->user()->waiter_calls();
        $sanboxNumber = Setting::all()->where('key', 'PhoneCode')->first()->value;
        $call_waiter_count = WaiterCall::all()->where('store_id', '=', auth()->user()->store_id)->count();

        return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'waiter_calls_count' => $calls->where('is_completed', '=', 0)->count(),
                    'waiter_calls' => $calls->values(),
                ]
            ], 200);

        // return view('waiters.waiter_calls', [
        //     'title' => 'Waiter Call',
        //     'count' => $calls->where('is_completed', '=', 0)->count(),
        //     'calls' => $calls,
        //     'root_name' => 'Waiter Call',
        //     'sanboxNumber' => $sanboxNumber,
        //     'call_waiter_count' => $call_waiter_count,
        // ]);
    }

    public function waiter_shifts()
    {
        return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'waiter_shifts' => json_decode(auth()->user()->waiter_shift->data),
                ]
            ], 200);

        // return view('waiters.waiter_calls', [
        //     'title' => 'Waiter Call',
        //     'count' => $calls->where('is_completed', '=', 0)->count(),
        //     'calls' => $calls,
        //     'root_name' => 'Waiter Call',
        //     'sanboxNumber' => $sanboxNumber,
        //     'call_waiter_count' => $call_waiter_count,
        // ]);
    }
}
