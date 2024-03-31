<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function create(Request $request){
        $data = $request->all();
        $ImgBase64 =  $request->image;
        $extension =  $request->image_extension;
        unset($data['image']);
        unset($data['image_extension']);

        if($ImgBase64!=NULL) {
            $imageName = Str::random(20) . '.' . $extension;
            Storage::disk('public')->put("stores/product/images/" . $imageName, base64_decode($ImgBase64));
            $data['image_url'] = "storage/stores/product/images/" . $imageName;
        }

        if(Product::create($data)) {
            $data= Product::all()->where('store_id',"=",$request->store_id)->sortByDesc('id');
            $response = array();
            foreach ($data as $key)
                $response[]=$key;

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response,
                ]
            ], 200);
        }else{
            return response()->json([
                "success"=> false,
                "status"=>"error",
                "error"=>[
                    "code"=>400,
                    "type"=>"Bad Request (ERROR:RT404)",
                    "message"=>"We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }


    }
    public function fetch(Request $request){
        if(Product::all()->where('restaurant_id','=',$request->shopId)){
            $temp = Product::all()->where('store_id','=',$request->shopId)->sortByDesc('id');
            $response = array();
            foreach ($temp as $key)
                $response[] = $key;
            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response
                ]
            ], 200);

        }else{

            return response()->json([
                "success"=> false,
                "status"=>"error",
                "error"=>["code"=>400,
                    "type"=>"data not found (ERROR:RT404)",
                    "message"=>"We are unable to process your request at this time. Please try again later"
                ],
            ], 400);

        }

    }
    public function update(Request $request){
        $data = $request->all();
        $ItemId = $data['ItemId'];
        $shopId = $data['shopId'];

        unset($data['shopId']);
        unset($data['ItemId']);

        if($request->image == NULL )
        {
            unset($data['image_extension']);
            unset($data['image']);
        }else{
            $ImgBase64 =  $request->image;
            $extension =  $request->image_extension;
            unset($data['image']);
            unset($data['image_extension']);
            $imageName = Str::random(20) . '.' . $extension;
            Storage::disk('public')->put("stores/product/images/" . $imageName, base64_decode($ImgBase64));
            $data['image_url'] = "storage/stores/product/images/" . $imageName;

        }

        if(Product::whereId($ItemId)->update($data)) {
            $data= Product::all()->where('store_id',"=",$shopId)->sortByDesc('id');
            $response = array();

            foreach ($data as $key)
                $response[]=$key;
            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    'data' => $response,

                ]
            ], 200);
        }else{
            return response()->json([
                "success"=> false,
                "status"=>"error",
                "error"=>[
                    "code"=>400,
                    "type"=>"Bad Request (ERROR:RT404)",
                    "message"=>"We are unable to process your request at this time. Please try again later"
                ],
            ], 400);
        }

    }

}
