<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Brian2694\Toastr\Facades\Toastr;
use DB;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class StoreController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $data = request()->validate([
            'store_name' => 'required',
            'email' => ['required', Rule::unique('stores', 'email')],
            'password' => 'required',
            'phone' => 'required',
            'logo_url' => 'image|mimes:jpeg,png,jpg,gif,svg|required',
            'address' => '',
            'description' => '',
            'theme_id' => '',
            'subscription_end_date' => 'required',
            'is_visible' => 'required'

        ]);

        $data['password'] = Hash::make($data['password']);
        if ($request->logo_url != NULL) {
            $url = $request->file("logo_url")->store('public/stores/logo');
            $data['logo_url'] = str_replace("public", "storage", $url);
        }
        $data['view_id'] = sha1(time());

        if ($store = Store::create($data)) {
            //store Image in s3
            // TO DO :: Store Logo Store in S#

            //assgin role and permissions
            $role = Role::where('name', 'owner')->first();
            $store->assignRole($role);
            $rolePermissions = DB::table("roles")->where("roles.id", $role->id)
                ->join("role_has_permissions", "roles.id", "role_has_permissions.role_id")
                ->pluck('role_has_permissions.permission_id')
                ->all();
            $store->syncPermissions($rolePermissions);
        }
        return Redirect::route("all_stores")->with(Toastr::success('Record added successfully ', 'Success'));
    }

    public function update(request $data, $id)
    {
        // dd($data->percentage_fee, $data->additional_fee);

        $request = request()->validate([
            'store_name' => 'required',
            'email' => '',
            'password' => '',
            'phone' => 'required',
            'logo_url' => '',
            'address' => '',
            'description' => '',
            'theme_id' => '',
            'subscription_end_date' => 'required',
            'is_visible' => 'required',
            'percentage_fee' => 'required|numeric',
            'additional_fee' => 'required|numeric'

        ]);

        unset($request['percentage_fee']);
        unset($request['additional_fee']);

        $percentage_fee = (float) $data->percentage_fee;
        $additional_fee = (float) $data->additional_fee;

        if ($data->logo_url != NULL) {
            Storage::delete(str_replace("storage", "public", Store::find($id)->logo_url));
            $url = $data->file("logo_url")->store('public/stores/logo');
            $request['logo_url'] = str_replace("public", "storage", $url);
        }
        if ($data->password == NULL)
            unset($request['password']);
        else
            $request['password'] = Hash::make($request['password']);

        $store = Store::findOrFail($id);

        if ($data->select_language != NULL) {

            $language_id = (int) $data->select_language;

            $translation = Translation::findOrFail($language_id);

            $store->store_translations()->attach($translation);

            // dd(auth('store')->user()->store_translations->count());
        } else {
            // dd($id);
            $store->store_translations()->detach();
        }

        if ($store->store_fee) {
            $store->store_fee()->update([
                'percentage_fee' => $percentage_fee,
                'additional_fee' => $additional_fee,
            ]);
        } else {
            $store->store_fee()->create([
                'percentage_fee' => $percentage_fee,
                'additional_fee' => $additional_fee,
            ]);
        }


        Store::whereId($id)->update($request);
        return Redirect::route("all_stores")->with(Toastr::success('Record added successfully ', 'Success'));
    }
}
