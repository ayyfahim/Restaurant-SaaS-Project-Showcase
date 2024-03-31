<?php

namespace App\Http\Controllers\StoreAdmin;

use App\KitchenLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;

class KitchenLocationController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function addkitchenlocation(Request $request)
    {
        $data = $request->validate([
            'location' => [
                'required',
                Rule::unique('kitchen_locations')->where(function ($query) {
                    $query->where('store_id', auth()->id());
                }),
            ]
        ]);

        $data['store_id'] = \auth()->id();

        KitchenLocation::create($data);
        return Redirect::route("store_admin.addproducts")->with(Toastr::success('Kitchen Location Added successfully ', 'Success'));
    }
}
