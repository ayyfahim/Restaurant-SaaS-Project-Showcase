<?php

namespace App\Http\Controllers\Admin;

use App\Allergen;
use App\Http\Controllers\API\DeliverectController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class AllergenController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }

    public function sync_allergens(Request $request)
    {
        try {
            $deliverectController = new DeliverectController();
            $deliverectController->getAllergensAndTags(1);
            return Redirect::route("all_allergens")->with(Toastr::success('Allergens syncronized successfully', 'Success'));
        } catch (\Throwable $th) {
            return Redirect::route("all_allergens")->with(Toastr::error('Something went wrong!', 'Error'));
        }
    }

    public function create_allergen(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'type' => 'required',
            'image_url' => 'image|mimes:jpeg,png,jpg,svg|max:2048|required',
            'active_image_url' => 'image|mimes:jpeg,png,jpg,svg|max:2048|required',
        ]);

        if ($request->image_url != NULL) {
            $url = $request->file("image_url")->store('public/allergen');
            $data['image_url'] = str_replace("public", "storage", $url);
        }

        if ($request->active_image_url != NULL) {
            $url = $request->file("active_image_url")->store('public/allergen');
            $data['active_image_url'] = str_replace("public", "storage", $url);
        }

        if (Allergen::create($data))
            return Redirect::route("all_allergens")->with(Toastr::success('Record added successfully ', 'Success'));
    }

    public function update_allergen(Request $request, Allergen $allergen)
    {
        $data = request()->validate([
            'name' => 'required',
            'type' => 'required',
            'image_url' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            'active_image_url' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->image_url != NULL) {
            Storage::delete(str_replace("storage", "public", $allergen->image_url));
            $url = $request->file("image_url")->store('public/allergen');
            $data['image_url'] = str_replace("public", "storage", $url);
        }

        if ($request->active_image_url != NULL) {
            Storage::delete(str_replace("storage", "public", $allergen->active_image_url));
            $url = $request->file("active_image_url")->store('public/allergen');
            $data['active_image_url'] = str_replace("public", "storage", $url);
        }

        $allergen->update($data);
        return Redirect::route("all_allergens")->with(Toastr::success('Record added successfully ', 'Success'));
    }

    public function delete_allergen(Allergen $allergen, Request $request)
    {
        try {
            Storage::delete(str_replace("storage", "public", $allergen->image_url));
            Storage::delete(str_replace("storage", "public", $allergen->active_image_url));
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($allergen->delete())
            return Redirect::route("all_allergens")->with(Toastr::success('Record Deleted successfully ', 'Success'));
    }
}
