<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Product;
use App\TimeRestriction;
use Illuminate\Http\Request;
use App\Models\AddonCategoryItem;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\Types\Null_;
use Str;

class ProductController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }


    public function addproducts(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            'image_url' => 'image|mimes:jpeg,png,jpg|max:2048',
            'cropped_image' => 'required|base64dimensions:ratio=16/9|base64mimes:jpeg,png,jpg',
            'is_active' => 'required',
            'category_id' => 'required',
            'allergens' => '',
            'food_preferences' => '',
            'is_veg' => '',
            'description' => '',
            'price' => 'required',
            'cooking_time' => 'required',
            'is_recommended' => '',
            'kitchen_location_id' => '',
            'store_id' => '',
            'sku' => '',
        ],[
            'image_url.image'=> 'Please upload jpeg, png, jpg format.', // custom message
            'image_url.mimes'=> 'Please upload jpeg, png, jpg format.', // custom message
            'image_url.max'=> 'Image must not be greater than 2mb.', // custom message
            'cropped_image.required'=> 'Please use a cropped image.', // custom message
            'cropped_image.base64dimensions'=> 'Image should have a 16:9 ratio.', // custom message
            'cropped_image.base64mimes'=> 'Image should be jpeg, png, jpg format.', // custom message
        ]);
        $data['store_id'] = auth()->id();
        // if ($request->image_url != NULL) {
        //     $url = $request->file("image_url")->store('public/stores/product/images/');
        //     $data['image_url'] = str_replace("public", "storage", $url);
        //     Image::make(file_get_contents($request->cropped_image))->save($data['image_url']);
        // }
        if (!empty($request->image_url)) {
            $store_id = $data['store_id'];
            $image = $request->image_url;
            $image_normal = Image::make($image)->fit(1920, 1080);
            $resource = $image_normal->stream();
            $extension = $image->extension();
            $imageName = Str::random(20) . '.' . $extension;
            Storage::disk('s3')->put("/products/$store_id/$imageName", $resource, 'public');
            $data['image_url'] =  "products/$store_id/" . $imageName;
        }
        $new_allergens = [];
        if (isset($data['allergens'])) {
            foreach ($data['allergens'] as $value) {
                $new_allergens[] = $value;
            }
        }
        if (isset($data['food_preferences'])) {
            foreach ($data['food_preferences'] as $value) {
                $new_allergens[] = $value;
            }
        }

        unset($data['cropped_image']);
        unset($data['allergens']);
        unset($data['food_preferences']);

        // dd($new_allergens);
        // die();
        $insert = Product::create($data);

        if ($insert) {


            if (isset($request->addon_category_id) && $request->addon_category_id[0] !== null) {

                $id = $insert->id;

                foreach ($request->addon_category_id as $addon_category_id) {
                    AddonCategoryItem::updateOrCreate([
                        'addon_category_id' => $addon_category_id,
                        'product_id' => $id,
                        'store_id' => auth()->id(),
                    ]);
                }
            }

            if (isset($new_allergens) && $new_allergens) {
                try {
                    $insert->allergens()->sync($new_allergens);
                    $insert->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }


            if ($request->time_restriction) {
                $time = TimeRestriction::findOrFail($request->time_restriction);
                $insert->time_restrictions()->save($time);
            }

            return Redirect::route("store_admin.products")->with(Toastr::success('Product Added successfully ', 'Success'));
        }
    }
    public function edit_products(Request $request, $id)
    {
        // dd($request->all());
        $data = request()->validate([
            'name' => 'required',
            'image_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cropped_image' => 'sometimes|base64dimensions:ratio=16/9|base64mimes:jpeg,png,jpg,gif,svg',
            'is_active' => 'required',
            'category_id' => 'required',
            'allergens' => '',
            'food_preferences' => '',
            'is_veg' => '',
            'description' => '',
            'price' => 'required',
            'cooking_time' => 'required',
            'is_recommended' => '',
            'kitchen_location_id' => '',
            'sku' => '',
        ]);

        // dd($data);

        $new_allergens = [];
        if (isset($data['allergens'])) {
            foreach ($data['allergens'] as $value) {
                $new_allergens[] = $value;
            }
        }
        if (isset($data['food_preferences'])) {
            foreach ($data['food_preferences'] as $value) {
                $new_allergens[] = $value;
            }
        }

        unset($data['allergens']);
        unset($data['food_preferences']);

        // if ($request->image_url != NULL) {
        //     Storage::delete(str_replace("storage", "public", Product::find($id)->image_url));
        //     $url = $request->file("image_url")->store('public/stores/category/images/');
        //     $data['image_url'] = str_replace("public", "storage", $url);
        //     Image::make(file_get_contents($request->cropped_image))->save($data['image_url']);
        // }
        $product = Product::findOrFail($id);

        if (!empty($request->image_url)) {
            $store_id = $product->store_id;
            $image = $request->image_url;
            $image_normal = Image::make($image)->fit(1920, 1080);
            $resource = $image_normal->stream();
            $extension = $image->extension();
            $imageName = Str::random(20) . '.' . $extension;
            Storage::disk('s3')->put("/products/$store_id/$imageName", $resource, 'public');
            $data['image_url'] =  "products/$store_id/" . $imageName;
        }


        $reqeust_index_number = (int) $request->index_number;

        // dd($reqeust_index_number);

        if ($reqeust_index_number == 0) {
            // dd($product->createIndexNumber());
            $data['index_number'] = $product->createIndexNumber();
        }

        if ($reqeust_index_number !== 0) {
            // dd($product->checkIndexNumber($reqeust_index_number, $request->category_id));
            if (!$product->checkIndexNumber($reqeust_index_number, $request->category_id)) {
                return back()->with(Toastr::error('This index number already exists on this given category', 'Error'));
            }
            $data['index_number'] = $reqeust_index_number;
        }

        // dd($data['index_number']);

        unset($data['cropped_image']);

        $insert = $product->update($data);

        // dd($request->addon_category_id);

        if ($insert) {

            if (isset($new_allergens) && !empty($new_allergens)) {
                try {
                    $product->allergens()->sync($new_allergens);
                    $product->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            } else {
                try {
                    $product->allergens()->detach();
                    $product->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            if (isset($request->addon_category_id) && $request->addon_category_id[0] !== null) {


                foreach ($request->addon_category_id as $addon_category_id) {

                    AddonCategoryItem::updateOrCreate([
                        'addon_category_id' => $addon_category_id,
                        'product_id' => $id,
                        'store_id' => auth()->id(),
                    ]);
                }

                $AddonCategoryItems = AddonCategoryItem::where('product_id', '=', $id)->get();

                $diffs = array_diff($AddonCategoryItems->pluck('addon_category_id')->toArray(), $request->addon_category_id);

                foreach ($diffs as $addon_category_id) {
                    $AddonCategoryItems->where('addon_category_id', $addon_category_id)->first()->delete();
                }
            } else {
                AddonCategoryItem::where('product_id', '=', $id)->delete();
            }

            if ($request->time_restriction) {
                // dd(TimeRestriction::findOrFail($request->time_restriction)->restrictionable());
                $time = TimeRestriction::findOrFail($request->time_restriction);
                $product->time_restrictions()->save($time);
            } else {
                // $product->time_restrictions()->delete();
                $product->time_restrictions()->wherePivot('restrictionable_id', '=', $product->id)->detach();
                // foreach ($product->time_restrictions as $restriction) {
                //     $restriction->restrictionable_id = null;
                //     $restriction->restrictionable_type = null;
                //     $restriction->save();
                // }
                // $product->time_restrictions()->delete();
            }
        }

        return Redirect::route("store_admin.products")->with(Toastr::success('Product Updated successfully ', 'Success'));
    }

    public function delete_product(Request $request)
    {
        $product = Product::find($request->id);
        if ($product->image_url !== null) {
            Storage::disk('s3')->delete("/$product->image_url");
        }
        $product->delete();
        AddonCategoryItem::destroy($request->product_id);
        return back()->with(Toastr::success('Product Deleted successfully ', 'Success'));
    }
}
