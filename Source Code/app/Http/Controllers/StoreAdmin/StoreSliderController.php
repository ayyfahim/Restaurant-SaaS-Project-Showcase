<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Http\Controllers\Controller;
use App\Models\StoreSlider;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class StoreSliderController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function add_slider(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'photo_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required',
            'is_visible' => 'required'
        ]);
        $data['store_id'] = auth()->id();
        $data['is_visible'] = $data['is_visible'] == "on"?1:0;
        if ($request->photo_url != NULL) {
            $url = $request->file("photo_url")->store('public/stores/slider/');
            $data['photo_url'] = str_replace("public", "storage", $url);
        }
        if (StoreSlider::create($data))
            return Redirect::route( "store_admin.banner" )->with(Toastr::success('Slider Added successfully ','Success'));
    }
    public function update_slider(Request $request, $id)
    {
        $data = request()->validate([
            'name' => 'required',
            'photo_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->photo_url != NULL) {
            Storage::delete(str_replace("storage", "public", StoreSlider::find($id)->photo_url));
            $url = $request->file("photo_url")->store('public/stores/slider/');
            $data['photo_url'] = str_replace("public", "storage", $url);
        }
        $data['store_id'] = auth()->id();
        $data['is_visible'] = isset($request['is_visible']) ? 1:0;
        StoreSlider::whereId($id)->update($data);
        return Redirect::route( "store_admin.banner" )->with(Toastr::success('Slider Updated successfully ','Success'));
    }
    public function delete_slider(Request $request)
    {
        if (Storage::delete(str_replace("storage", "public", StoreSlider::find($request->id)->photo_url))) {
            StoreSlider::destroy($request->id);
        }
        return back()->with(Toastr::success('Slider Deleted successfully ','Success'));

    }
}
