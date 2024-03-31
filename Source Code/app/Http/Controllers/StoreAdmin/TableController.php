<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;

class TableController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function add_table(Request $request)
    {
        $data = request()->validate([
            'table_name' => 'required',
            'table_number' => '',
        ]);

        $data['store_id'] = auth()->id();
        $data['is_active'] = isset($request['is_active']) ? 1:0;

        if (Table::create($data))
            return Redirect::route( "store_admin.all_tables" )->with(Toastr::success('Table Added successfully ','Success'));
    }

    public function edit_table(Request $request,$id){

        $data = request()->validate([
            'table_name'=>'required',
            'table_number' => '',

        ]);
        $data['store_id'] = auth()->id();
        $data['is_active'] = isset($request['is_active']) ? 1:0;


        if(Table::whereId($id)->update($data)) {
            return Redirect::route( "store_admin.all_tables" )->with(Toastr::success('Table Updated successfully ','Success'));
        }
    }
}
