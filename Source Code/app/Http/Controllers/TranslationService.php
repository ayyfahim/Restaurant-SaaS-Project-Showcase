<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Translation;
use App\MenuTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TranslationService extends Controller
{
    public function language_switcher(Request $request)
    {


        request()->validate([
            'selected_language' => 'required',
        ]);
        Session::put('selected_language', $request->selected_language);

        return back();
    }

    public function languages()
    {
        return Translation::all()->where("is_active", "=", 1);
    }

    public function selected_language()
    {
        if (Session::has('selected_language'))
            return Translation::find(Session::get('selected_language'));
        else
            return Translation::all()->where("is_default", "=", 1)->first();
    }

    public function selected_language_api($language_id, $store_id)
    {

        if ($language_id != NULL)
            return Translation::find($language_id);
        else

        if ($store_id != NULL) {

            if ($store = Store::where('view_id', $store_id)->first()) {
                if ($store->store_translations->count() > 0) {
                    return $store->store_translations->last();
                } else {
                    return Translation::all()->where("is_default", "=", 1)->first();
                }
            }
        }

        return Translation::all()->where("is_default", "=", 1)->first();
    }

    public function selected_menu_translations($menu_id)
    {
        if (Session::has('selected_language')){
            $translationName = Translation::find(Session::get('selected_language'));
            return MenuTranslation::where('language', $translationName->language_name)->where('menu_id', $menu_id)->first();
        }
        else{
            return MenuTranslation::all()->where("is_active", "=", 1)->where('menu_id', $menu_id)->first();
        }
    }
}
