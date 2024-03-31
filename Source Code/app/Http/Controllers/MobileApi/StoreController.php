<?php

namespace App\Http\Controllers\MobileAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function getStore(Request $request, $view_id, $table_id = null){

        if( $store = Store::where('view_id', $view_id)->first()){
            return response()->json([
               "status" => "success",
               "code" => 200,
               "data" => $store
           ], 200);
        }else{
           return response()->json([
               "status" => "error",
               "code" => 404,
               "message" => "Store not found"
           ], 404);
        }
    }
}
