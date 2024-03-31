<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FcmNotification;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function save_store_fcm_token(Request $request){
       $data = $request->all();
        if(FcmNotification::create($data)) {
            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => null,
                ]
            ], 200);
        }
    }
}
