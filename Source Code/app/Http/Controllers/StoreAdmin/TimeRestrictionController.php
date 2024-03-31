<?php

namespace App\Http\Controllers\StoreAdmin;

use App\TimeRestriction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;

class TimeRestrictionController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }
    
    public function addtimerestrictions(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_timing' => 'required',
            'end_timing' => 'required',
        ]);

        // $data['store_id'] = auth()->id();

        $data = [
            'start_time' => $request->start_timing,
            'end_time' => $request->end_timing
        ];

        if ($created = TimeRestriction::create([
            'name' => $request->name,
            'data' => $data,
            'store_id' => auth()->id(),
        ]))
            return back()->with(Toastr::success('Status Updated successfully ', 'Success'));
    }

    public function edittimerestrictions(Request $request, $id)
    {
        $time_restriction = TimeRestriction::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'start_timing' => 'required',
            'end_timing' => 'required',
        ]);

        $data = [
            'start_time' => $request->start_timing,
            'end_time' => $request->end_timing
        ];

        if ($updated = $time_restriction->update([
            'name' => $request->name,
            'data' => $data,
        ]))

            return Redirect::route("store_admin.edittimerestrictions", $id)->with(Toastr::success('Time Restriction Updated successfully ', 'Success'));
    }

    public function deletetimerestrictions($id)
    {
        $time_restriction = TimeRestriction::findOrFail($id);

        if ($time_restriction->delete())
            return Redirect::route("store_admin.addtimerestrictions")->with(Toastr::success('Time Restriction Deleted successfully ', 'Success'));
    }
}
