<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;

class MenuController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function add_menues(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'is_active' => 'required',
            'store_id' => '',
        ]);

        $data['store_id'] = auth()->id();
        if (Menu::create($data))
            return Redirect::route("store_admin.menues")->with(Toastr::success('Menu Added successfully ', 'Success'));
    }

    public function update_menues(Request $request, $id)
    {
        $data = request()->validate([
            'name' => 'required',
            'is_active' => 'required',
        ]);

        $menu = Menu::whereId($id)->first();

        // $reqeust_index_number = (int) $request->index_number;

        // // dd($reqeust_index_number);

        // if ($reqeust_index_number == 0) {
        //     // dd($product->createIndexNumber());
        //     $data['index_number'] = $category->createIndexNumber();
        // }

        // if ($reqeust_index_number !== 0) {
        //     // dd($product->checkIndexNumber($reqeust_index_number));
        //     if (!$category->checkIndexNumber($reqeust_index_number)) {
        //         return back()->with(Toastr::error('This index number already exists on this given category', 'Error'));
        //     }
        //     $data['index_number'] = $reqeust_index_number;
        // }

        $menu->update($data);

        return Redirect::route("store_admin.menues")->with(Toastr::success('Menu Updated successfully ', 'Success'));
    }

    public function delete_menu(Request $request)
    {
        // if (Storage::delete(str_replace("storage", "public", Category::find($request->id)->image_url))) {
            Menu::destroy($request->id);
        // }
        return back()->with(Toastr::success('Menu Deleted successfully ', 'Success'));
    }
}
