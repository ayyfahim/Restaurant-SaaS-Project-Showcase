<?php

namespace App\Http\Controllers\WEBAPI;

use App\Allergen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllergenController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:customer');
    }

    public function fetchAllAllergens(Request $request)
    {
        $allergens = [];
        $user = $request->user()->load('allergens');

        $get_allergens = Allergen::all();

        foreach ($get_allergens as $value) {
            // $value['matched'] = $user->allergens->pluck('id')->contains($value->id) ? true : false;
            $allergens[] = $value;
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'allergens' => $allergens,
                'user' => $user,
            ]
        ], 200);
    }

    public function addAllergens(Request $request)
    {
        $allergens = [];
        $user = $request->user();
        $user_allergens = [];

        foreach ($request->allergens as $data) {
            $user_allergens[] = $data['id'];
        }

        $user->allergens()->sync($user_allergens);

        $get_allergens = Allergen::all();

        foreach ($get_allergens as $value) {
            // $value['matched'] = $user->allergens->pluck('id')->contains($value->id) ? true : false;
            $allergens[] = $value;
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'allergens' => $allergens,
                'user' => $user->load('allergens'),
            ]
        ], 200);
    }
}
