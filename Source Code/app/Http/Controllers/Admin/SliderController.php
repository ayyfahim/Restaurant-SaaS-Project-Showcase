<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }
    public function add_slider(Request $request){
        $data = request()->validate([
            'name'=>'required',
            'photo_url'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required',
            'is_visible'=>'required'
        ]);
        if($request->photo_url !=NULL) {
            $url = $request->file("photo_url")->store('public/stores/slider/');
            $data['photo_url'] = str_replace("public","storage",$url);
        }
        if(Slider::create($data))
            return back()->with("MSG","Record added successfully")->with("TYPE", "success");
    }
    public function update_slider(Request $request,$id){
        $data = request()->validate([
            'name'=>'required',
            'photo_url'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_visible'=>'required'
        ]);
        if($request->photo_url !=NULL) {
            Storage::delete(str_replace("storage","public",Slider::find($id)->photo_url));
            $url = $request->file("photo_url")->store('public/stores/slider/');
            $data['photo_url'] = str_replace("public","storage",$url);
        }
        Slider::whereId($id)->update($data);
        return back()->with("MSG", "Record Updated Successfully.")->with("TYPE", "success");


    }
    public function delete_slider(Request $request){
           if(Storage::delete(str_replace("storage","public",Slider::find($request->id)->photo_url))) {
               Slider::destroy($request->id);
           }
           return back();
    }
}
