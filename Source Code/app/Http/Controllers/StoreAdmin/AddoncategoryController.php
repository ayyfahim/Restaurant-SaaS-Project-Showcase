<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Models\Addon;
use Illuminate\Http\Request;
use App\Models\AddonCategory;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;

class AddoncategoryController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function add_addoncategory(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'type' => 'required',
            'sku' => 'nullable|unique:addon_categories,sku',
            'minimum_amount' => 'required|integer',
            'maximum_amount' => 'required|integer',
        ]);

        $data['store_id'] = auth()->id();


        if (isset($request->multi_select)) {
            $data['multi_select'] = (bool) true;
        } else {
            $data['multi_select'] = (bool) false;
        }

        if ($created = AddonCategory::create($data))
            return Redirect::route("store_admin.addon_categories_edit", $created->id);
        // return back()->with("MSG", "Record added successfully")->with("TYPE", "success");
    }

    public function update_addoncategory(Request $request, $id)
    {
        $data = request()->validate([
            'name' => 'required',
            'type' => 'required',
            'sku' => 'nullable|unique:addon_categories,sku,' . $id,
            'minimum_amount' => 'required|integer',
            'maximum_amount' => 'required|integer',
        ]);
        $data['store_id'] = auth()->id();

        if (isset($request->multi_select)) {
            $data['multi_select'] = (bool) true;
        } else {
            $data['multi_select'] = (bool) false;
        }

        if (AddonCategory::whereId($id)->update($data))
            return back()->with("MSG", "Record added successfully")->with("TYPE", "success");
    }

    public function add_addon(Request $request)
    {
        $data = request()->validate([
            'addon_name' => 'required',
            'addon_category_id' => 'required',
            'price' => 'required',
            'kitchen_location_id' => 'required',
            'sku' => '',
        ]);


        $data['store_id'] = auth()->id();

        if (Addon::create($data))
            return back()->with("MSG", "Record added successfully")->with("TYPE", "success");
    }



    public function update_addon(Request $request, $id)
    {
        $data = request()->validate([
            'addon_name' => 'required',
            'addon_category_id' => 'required',
            'price' => 'required',
            'kitchen_location_id' => 'required',
            'sku' => '',
        ]);

        $addon = Addon::whereId($id);

        if ($addon->update($data)) {
            $addon = $addon->first();
            $addon->nested_addons()->delete();
            if (isset($request->nested_addons) && !empty($request->nested_addons)) {
                foreach ($request->nested_addons as $nested_addon) {
                    // $find_nested_addon = AddonCategory::find($nested_addon);
                    $addon->nested_addons()->create([
                        'addon_category_id' => $nested_addon,
                        'store_id' => auth()->user()->id
                    ]);
                }
            }

            
            return back()->with("MSG", "Record Updated Successfully.")->with("TYPE", "success");
        }
    }


    public function delete_addon(Request $request)
    {

        Addon::destroy($request->id);

        return back()->with(Toastr::success('Addon Deleted successfully ', 'Success'));
    }

    public function delete_addoncategories(Request $request)
    {

        AddonCategory::destroy($request->id);

        return back()->with(Toastr::success('Addon Category Deleted successfully ', 'Success'));
    }
}
