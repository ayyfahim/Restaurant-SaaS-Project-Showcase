<?php

namespace App\Http\Controllers\StoreAdmin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Image;

class CategoryController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }
    public function add_category(Request $request)
    {
        $data = request()->validate([
            'name' => 'required',
            // 'image_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'cropped_image' => 'required|base64dimensions:ratio=16/9|base64mimes:jpeg,png,jpg,gif,svg',
            'is_active' => 'required',
            'menu_id' => 'required',
            'store_id' => '',
            'kitchen_location_id' => '',
        ]);
        $data['store_id'] = auth()->id();
        // if ($request->image_url != NULL) {
        //     $url = $request->file("image_url")->store('public/stores/category/images');
        //     $data['image_url'] = str_replace("public", "storage", $url);
        //     Image::make(file_get_contents($request->cropped_image))->save($data['image_url']);
        // }
        // unset($data['cropped_image']);

        // if (!empty($request->image_url)) {
        //     $store_id = $data['store_id'];
        //     $image = $request->image_url;
        //     $image_normal = Image::make($image)->fit(1920, 1080);
        //     $resource = $image_normal->stream();
        //     $extension = $image->extension();
        //     $imageName = Str::random(20) . '.' . $extension;
        //     Storage::disk('s3')->put("/catogories/$store_id/$imageName", $resource, 'public');
        //     $data['image_url'] =  "catogories/$store_id/" . $imageName;
        // }

        if (Category::create($data))
            return Redirect::route("store_admin.categories")->with(Toastr::success('Category Added successfully ', 'Success'));
    }

    public function update_category(Request $request, $id)
    {
        $data = request()->validate([
            'name' => 'required',
            'image_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cropped_image' => 'sometimes|base64dimensions:ratio=16/9|base64mimes:jpeg,png,jpg,gif,svg',
            'is_active' => 'required',
            'index_number' => 'required|unique:categories,index_number,' . $id . ',id,store_id,'. auth()->user()->id,
            'menu_id' => 'required',
            'kitchen_location_id' => 'nullable',
        ]);

        $category = Category::find($id);

        $reqeust_index_number = (int) $request->index_number;
        if ($reqeust_index_number == 0) {
            $data['index_number'] = $category->createIndexNumber();
        }

        if ($reqeust_index_number !== 0 && $reqeust_index_number !== $category->index_number) {
            if (!$category->checkIndexNumber($reqeust_index_number)) {
                return back()->with(Toastr::error('This index number already exists on this given category', 'Error'));
            }
            $data['index_number'] = $reqeust_index_number;
        }

        // if ($request->image_url != NULL) {
        //     Storage::delete(str_replace("storage", "public", Category::find($id)->photo_url));
        //     $url = $request->file("image_url")->store('public/stores/category/images');
        //     $data['image_url'] = str_replace("public", "storage", $url);
        //     Image::make(file_get_contents($request->cropped_image))->save($data['image_url']);
        // }

        unset($data['cropped_image']);

        if (!empty($request->image_url)) {
            $store_id = $category->store_id;
            $image = $request->image_url;
            $image_normal = Image::make($image)->fit(1920, 1080);
            $resource = $image_normal->stream();
            $extension = $image->extension();
            $imageName = Str::random(20) . '.' . $extension;
            Storage::disk('s3')->put("/catogories/$store_id/$imageName", $resource, 'public');
            $data['image_url'] =  "catogories/$store_id/" . $imageName;
        }


        $category->update($data);

        return Redirect::route("store_admin.categories")->with(Toastr::success('Category Updated successfully ', 'Success'));
    }

    public function delete_category(Request $request)
    {
        $category = Category::find($request->id);
        if($category->image_url !== null){
            Storage::disk('s3')->delete("/$category->image_url");
        }
        $category->delete();
        return back()->with(Toastr::success('Category Deleted successfully ', 'Success'));
    }
}
