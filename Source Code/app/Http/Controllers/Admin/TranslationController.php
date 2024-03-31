<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

class TranslationController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }
    public function add_translation(Request $request){
        $data = $request->all();
         request()->validate([
            'language_name'=>'required',
        ]);
        unset($data['is_active']);
        unset($data['is_default']);
        unset($data['is_rlt']);
        unset($data['_token']);
        $is_active =$request->is_active == "on"?1:0;
        $is_default =$request->is_default == "on"?1:0;
        $is_rlt =$request->is_rlt == "on"?1:0;

        $language = new Translation();
        $language->language_name = $request->language_name;
        $language->is_active = $is_active;
        $language->is_rlt = $is_rlt;
        $language->is_default = $is_default;
        $language->data = $data;

        if($is_default == 1)
            Translation::where('is_default',1)->update(['is_default'=>0]);

        if($language->save())
            return back()->with(Toastr::success('Record added successfully', 'Success'));

    }
    public function update_translation(Request $request,$id){
        $data = $request->all();
        request()->validate([
            'language_name'=>'required',
        ]);
        unset($data['is_active']);
        unset($data['is_default']);
        unset($data['is_rlt']);
        unset($data['_token']);
        unset($data['_method']);
        $is_active =$request->is_active == "on"?1:0;
        $is_default =$request->is_default == "on"?1:0;
        $is_rlt =$request->is_rlt == "on"?1:0;

        $language =Translation::find($id);
        $language->language_name = $request->language_name;
        $language->is_active = $is_active;
        $language->is_rlt = $is_rlt;
        $language->is_default = $is_default;
        $language->data = $data;

        if($is_default == 1)
            Translation::where('is_default',1)->update(['is_default'=>0]);

        if($language->save())
            return back()->with(Toastr::success('Record Updated successfully','Success'));


        }

}
