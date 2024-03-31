<?php

namespace App\Http\Controllers\API;

use App\Allergen;
use App\Models\Order;
use App\Models\Store;
use App\Models\Addon;
use App\Models\AddonCategory;
use App\Models\AddonCategoryItem;
use App\Product;
use App\Category;
use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Controllers\StoreAdmin\UpdateOrderStatusController;
use App\NestedAddon;
use App\MenuTranslation;
use App\StoreOpenHour;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\json_decode;
use Illuminate\Support\Facades\Http;
use Image;
use Storage;
use Str;

class DeliverectController extends Controller
{
    public function ChannelStatus(Request $request)
    {
        $url = config('app.url');
        return response()->json([
            "statusUpdateURL" => $url . "/api/orderStatusUpdate",
            "menuUpdateURL" => $url . "/api/menuUpdate",
            "snoozeUnsnoozeURL" => $url . "/api/snoozeUnsnooze",
            "busyModeURL" => $url . "/api/busyMode",
        ], 200);
    }

    public function createOrder(Request $request)
    {
        // dd(now()->toIso8601ZuluString());

        $user = new Customer(['email' => 'ayyfahim@gmail.com']);
        $result = \Auth::login($user);
        dd($user);

        die();

        $order = Order::find(476);
        foreach ($order->orderDetails as $item) {
            $subItems = [];

            foreach ($item->OrderDetailsExtraAddon as $addon) {
                $subItems[] = [
                    "plu" => (string) $addon->sku,
                    "name" => (string) $addon->addon_name,
                    "price" => (float) $addon->addon_price,
                    "quantity" => $addon->addon_count,
                    "remark" => "",
                    "subItems" => []
                ];
            }

            $items[] = [
                "plu" => (string) $item->sku,
                "name" => (string) $item->name,
                "price" => (float) $item->price,
                "quantity" => $item->quantity,
                "remark" => "",
                "subItems" => $subItems
            ];
        }

        $array = [
            "channelOrderId" => (string) $order->order_unique_id,
            "channelOrderDisplayId" => (string) $order->id,
            "channelLinkId" => "607ff2f74a13c7014606de65",
            "by" => "",
            "orderType" => 3,
            "channel" => 10000,
            "pickupTime" => now()->toIso8601ZuluString(),
            "estimatedPickupTime" => now()->toIso8601ZuluString(),
            "deliveryTime" => now()->toIso8601ZuluString(),
            "deliveryIsAsap" => true,
            "courier" => "restaurant",
            "customer" => [
                "name" =>  "No Name Provided",
                "companyName" => "Deliverect",
                "phoneNumber" => (string) $order->customer_phone ?? "No Phone Provided",
                "email" =>  auth('customer')->user()->email ?? "No Email Provided"
            ],
            "deliveryAddress" => [
                "street" => "The Krook",
                "streetNumber" => "4",
                "postalCode" => "9000",
                "city" => "Gent",
                "extraAddressInfo" => ""
            ],
            "orderIsAlreadyPaid" => false,
            "payment" => [
                "amount" => (float) $order->total,
                "type" => 0
            ],
            "note" =>  $order->comments ?? null,
            "items" =>
            $items,
            "decimalDigits" => 2,
            "numberOfCustomers" => 1,
            "deliveryCost" => 0,
            "serviceCharge" => 0,
            "discountTotal" => 0,
            "tip" => 0
        ];

        // dd($array);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6ImdDN25CdHNHQmVFRzZlRXIifQ.eyJpc3MiOiJodHRwczovL2FwaS5zdGFnaW5nLmRlbGl2ZXJlY3QuY29tIiwiYXVkIjoiaHR0cHM6Ly9hcGkuZGVsaXZlcmVjdC5jb20iLCJleHAiOjE2MjI5NzgzMTMsImlhdCI6MTYyMjg5MTkxMywic3ViIjoiQ0cwSFZNWVN4WWVaeVVGcEBjbGllbnRzIiwiYXpwIjoiQ0cwSFZNWVN4WWVaeVVGcCIsInNjb3BlIjoiZ2VuZXJpY0NoYW5uZWw6YXBwZXRpenIifQ.k99L-fg7nr--9RhhiEJeSbb9xykk4PttB-4i34aXSXqNoeqaetbDWJETTbLYtvpMUo4IR51-Jnt3CnH6MK2WkbsP1zRdkywpCqpiNub0zCIojpJAZYmIHavCbBZy1qsnDTiycIxwx0VqpqtX8bQDS1VryxgKeFZTBCoLKEDjgpSjuG0Enrf57twaahdMcMGueXMZhEU0lXbFhWlCDMMceHFQ1f8vuoKbLSe8U8WHMnclFJbETPcViXR6DgXpdAXDJPBoWAJUO7GmY8GY6_LUuDOmRRhVJkuJUWdODuAxw6kAZdrPQujYcqkHuLK79UBCDdDG-dZNlMjeKnICtOzWBw',
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json; charset=utf-8', // Get
        ])
            ->post(
                'https://api.staging.deliverect.com/appetizr/order/607ff2f74a13c7014606de65',
                $array
            );

        // dd($response);

        $body = (string) $response->getBody();

        dd($body);

        return response()->json([
            "statusUpdateURL" => "https=>//integrator.com/statusUpdate",
            "menuUpdateURL" => "https=>//integrator.com/menuUpdate",
            "snoozeUnsnoozeURL" => "https=>//integrator.com/snoozeUnsnooze",
            "busyModeURL" => "https=>//integrator.com/busyMode",
        ], 200);
    }

    public function createToken()
    {
        $new_order = Order::find(494);

        // dd($new_order->table->kitchen_orders);

        $unpaid_orders = $new_order->table->kitchen_orders;

        if ($unpaid_orders->count() > 0) {
            $group_id = $unpaid_orders->first()->order_group_id ? $unpaid_orders->first()->order_group_id : "ODRGRP" . time();

            $new_order->order_group_id = $group_id;
            // $new_order->save();
        } else {
            $group_id = "ODRGRP" . time();
            $new_order->order_group_id = $group_id;
            // $new_order->save();
        }

        dd($group_id);

        die();
        $order = Order::find(482);
        dd(floor($this->toFixed($order->total, 2) * 1000) / 1000);

        $store = Store::findOrFail($order->store_id);

        $response = Http::post(
            'https://api.staging.deliverect.com/oauth/token',
            [
                "client_id" => $store->deliverect_api_key,
                "client_secret" => $store->deliverect_api_secret_key,
                "audience" => "https://api.staging.deliverect.com",
                "grant_type" => "client_credentials"
            ]
        );

        $body = (string) $response->getBody();

        // dd($body);

        return $array = [
            "access_token" => json_decode($body)->access_token,
            "location" => $store->deliverect_channel_link_id
        ];
    }

    public function toFixed($number, $decimals)
    {
        return number_format($number, $decimals, '.', "");
    }

    public function getUser($id)
    {
        $store = Store::find($id);
        return $store;
    }

    // Comman function to generate deliverect token
    public function generateToken($store)
    {
        $deliverect_api_key = $store->deliverect_api_key;
        $deliverect_api_secret_key = $store->deliverect_api_secret_key;
        try {
            $response = Http::post(
                'https://api.staging.deliverect.com/oauth/token',
                [
                    "client_id" => $deliverect_api_key,
                    "client_secret" => $deliverect_api_secret_key,
                    "audience" => "https://api.staging.deliverect.com",
                    "grant_type" => "client_credentials"
                ]
            );
            $bodyStr = (string) $response->getBody();
            $body = json_decode($bodyStr);
            return $body;
        } catch (\Throwable $th) {
            return $response =  response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    "message" => $th
                ]
            ], 400);
        }
    }

    public function getAllergensAndTags($id)
    {
        try {
            $store = $this->getUser($id);
            $body = $this->generateToken($store);
            $headers = [
                'Authorization' => $body->token_type . " " . $body->access_token,
                'Content-Type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json; charset=utf-8',
            ];
            $httpResponse = Http::withHeaders($headers)
                ->get('https://api.staging.deliverect.com/allAllergens');
            $bodyStr = (string) $httpResponse->getBody();
            $body = json_decode($bodyStr);

            $allergenIds = [];
            $allergens = [];
            foreach ($body as $key => $item) {
                $allergen = [
                    "id" => $item->allergenId,
                    "name" => $item->name,
                    "image_url" => file_exists("storage/allergen/" . str_replace(" ", "_", strtolower($item->name)) . ".png") ? "storage/allergen/" . str_replace(" ", "_", strtolower($item->name)) . ".png" : "storage/allergen/notfound.png",
                    "active_image_url" => file_exists("storage/allergen/" . str_replace(" ", "_", strtolower($item->name)) . ".png") ? "storage/allergen/" . str_replace(" ", "_", strtolower($item->name)) . "_active.png" : "storage/allergen/notfound_active.png",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now()
                ];
                $allergenIds[] = $item->allergenId;
                $allergens[] = $allergen;
            }

            Allergen::whereIn('id', $allergenIds)->delete();
            Allergen::insert($allergens);

            return response()->json([
                "success" => true,
                "status" => "success",
                "payload" => [
                    "allergens" => $allergens,
                ]
            ], 200);
        } catch (\Throwable $th) {
            $response =  response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    "message" => $th
                ]
            ], 400);
        }
    }

    public function updateUpdateChannelStatus($id, $request)
    {
        try {
            $store = $this->getUser($id);
            $body = $this->generateToken($store);
            $headers = [
                'Authorization' => $body->token_type . " " . $body->access_token,
                'Content-Type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json; charset=utf-8',
            ];
            $request = (object)$request;
            $httpResponse = Http::withHeaders($headers)
                ->post(
                    'https://api.staging.deliverect.com/appetizr/updateStoreStatus/' . $store->deliverect_channel_link_id,
                    [
                        "status" => $request->status,
                        "reason" => $request->reason,
                    ]
                );
            return $httpResponse;
        } catch (\Throwable $th) {
            $response =  response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    "message" => $th
                ]
            ], 400);
        }
    }

    public function updateOrderRating($id, $request)
    {
        try {
            $order = Order::find($id);
            $store = $this->getUser($order->store_id);
            $body = $this->generateToken($store);
            $headers = [
                'Authorization' => $body->token_type . " " . $body->access_token,
                'Content-Type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json; charset=utf-8',
            ];
            return $httpResponse = Http::withHeaders($headers)
                ->post(
                    'https://api.staging.deliverect.com/appetizr/updateRating',
                    [
                        "channelOrderId" => $request['orderId'],
                        "orderDate" => $request['orderDate'],
                        "channelLinkId" => $store->deliverect_channel_link_id,
                        "rating" => [$request['rating']]
                    ]
                );
        } catch (\Throwable $th) {
            $response =  response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    "message" => $th
                ]
            ], 400);
        }
    }

    public function updateOrder(Request $request)
    {
        \Log::info(json_encode($request->all()));
        $status = $request->status;
        $order = new UpdateOrderStatusController();
        switch ($request->status) {
            case '20':
                $request['status'] = 2;
                break;
            case '70':
                $request['status'] = 5;
                break;
            case '90':
                $request['status'] = 4;
                break;
            case '110':
                $request['status'] = 3;
                break;
            case '120':
                $request['status'] = 3;
                break;
        }
        \Log::info($request->status);
        if (in_array($request->status, ['3', '4', '5'])) {
            $order->updateStatus($request, $request->channelOrderId);
        }
        $data = $request->all();
        $data['status'] = $status;
        $data['timeStamp'] = Date(now());

        return response()->json($data, 200);
    }

    public function updateMenu(Request $request)
    {
        try {
            \Log::info(json_encode($request->all()));
            ini_set('max_execution_time', 300);
            $channelLink = "";
            $createdCategories = [];
            $createdProducts = [];
            $createdAddons = [];
            $createdAddonCategories = [];
            $createdTranslations = [];
            $translation = (object)[];
            foreach ($request->all() as $key => $item) {
                $item = (object)$item;
                $store = Store::where('deliverect_channel_link_id', $item->channelLinkId)->with('kitchen_locations')->first();
                if (!$store) {
                    return response()->json([
                        'categories' => [],
                        'products' => [],
                    ], 200);
                }

                // menu availabilities

                $dayName = [
                    '1' => 'monday',
                    '2' => 'tuesday',
                    '3' => 'wednesday',
                    '4' => 'thursday',
                    '5' => 'friday',
                    '6' => 'saturday',
                    '7' => 'sunday',
                ];
                foreach ($item->availabilities as $key => $value) {
                    if(in_array($value['dayOfWeek'], array_keys($dayName))){
                        $dayKeys[] = $value['dayOfWeek'];
                        $dayValue = $dayName[$value['dayOfWeek']];
                        $arrayData[$dayValue] = [
                            'start_time' => $value['startTime'] . ':00',
                            'end_time' => $value['endTime'] . ':00'
                        ];
                    }
                }
                foreach (array_diff(array_keys($dayName), $dayKeys) as $key => $value) {
                    $dayValue = $dayName[$value];
                    $arrayData[$dayValue] = [
                        'start_time' => "-1",
                        'end_time' => "-1"
                    ];
                }
                $StoreOpenHour = StoreOpenHour::where('store_id', $store->id)->first();
                $StoreOpenHour->data = $arrayData;
                $StoreOpenHour = $StoreOpenHour->save();

                $deliverectController = new DeliverectController();
                $deliverectController->getAllergensAndTags(1);

                if ($channelLink != $item->channelLinkId) {
                    AddonCategory::where('store_id', $store->id)->delete();
                    Addon::where('store_id', $store->id)->delete();
                    AddonCategoryItem::where('store_id', $store->id)->delete();
                    Category::where('store_id', $store->id)->delete();
                    Product::where('store_id', $store->id)->delete();
                    NestedAddon::where('store_id', $store->id)->delete();
                    Menu::where('store_id', $store->id)->delete();
                    Storage::disk('s3')->deleteDirectory("/catogories/$store->id");
                    Storage::disk('s3')->deleteDirectory("/products/$store->id");
                    $channelLink = $item->channelLinkId;
                }

                $main_kitchen = $store->kitchen_locations[0]->id;
                foreach ($store->kitchen_locations as $kitchen) {
                    if ($kitchen['is_main'] == 1) {
                        $main_kitchen = $kitchen['id'];
                    }
                }

                $menu = Menu::create([
                    "name" => $item->menu,
                    "is_active" => 1,
                    "store_id" => $store->id
                ]);

                $menu_id = $menu->id;
                $translation->$menu_id = (object)[];

                if (count($item->menuTranslations) > 0) {
                    foreach ($item->menuTranslations as $key => $value) {
                        $translation->$menu_id->$key['menu_name'] = $value;
                    }
                }

                foreach ($item->modifierGroups as $modifierGroup) {
                    $addon_category = [];
                    $addon_category['name'] = $modifierGroup['name'];
                    $addon_category['type'] = 'EXT';
                    if ($modifierGroup['min'] == $modifierGroup['max'] && $modifierGroup['max'] != 0) {
                        $addon_category['type'] = 'SNG';
                        $addon_category['multi_select'] = true;
                    }
                    $addon_category['store_id'] = $store->id;
                    $addon_category['sku'] = $modifierGroup['plu'];
                    $addon_category['minimum_amount'] = $modifierGroup['min'];
                    $addon_category['maximum_amount'] = $modifierGroup['max'];
                    $addon_cat = AddonCategory::updateOrCreate([
                        'sku' => $modifierGroup['plu']
                    ], $addon_category);
                    $createdAddonCategories[$modifierGroup['_id']] = $addon_cat;


                    if (count($modifierGroup['nameTranslations']) > 0) {
                        foreach ($modifierGroup['nameTranslations'] as $key => $value) {
                            $translation->$menu_id->$key[$modifierGroup['name']] = $value;
                        }
                    }

                    if (count($modifierGroup['subProducts']) > 0) {
                        foreach ($modifierGroup['subProducts'] as $modifier) {
                            $itemModifier = $item->modifiers[$modifier];
                            $addon = [];
                            $addon['addon_name'] = $itemModifier['name'];
                            $addon['price'] = floatval(floatval($itemModifier['price']) / 100);
                            $addon['addon_category_id'] = $addon_cat->id;
                            $addon['store_id'] = $store->id;
                            $addon['sku'] = $itemModifier['plu'];
                            $addon['kitchen_location_id'] = $main_kitchen;
                            $newAddon = Addon::updateOrCreate([
                                'sku' => $itemModifier['plu'],
                                'addon_category_id' => $addon['addon_category_id']
                            ], $addon);
                            $createdAddons[$modifier] = $newAddon;

                            if (count($itemModifier['nameTranslations']) > 0) {
                                foreach ($itemModifier['nameTranslations'] as $key => $value) {
                                    $translation->$menu_id->$key[$itemModifier['name']] = $value;
                                }
                            }

                            if (count($itemModifier['subProducts']) > 0) {
                                foreach ($itemModifier['subProducts'] as $nested_modifier) {
                                    $modifierGroupItemName = $item->modifierGroups[$nested_modifier]['name'];
                                    $modifierGroupItemId = AddonCategory::where('name', $modifierGroupItemName)->first();
                                    $itemNestedModifier = [
                                        "addon_category_id" => $modifierGroupItemId['id'] ?? $addon_cat->id,
                                        "nested_addon_id" => $newAddon->id,
                                        "store_id" => $store->id
                                    ];
                                    $newNestedAddon = NestedAddon::updateOrCreate([
                                        "addon_category_id" => $modifierGroupItemId['id'] ?? $addon_cat->id,
                                        "nested_addon_id" => $newAddon->id
                                    ], $itemNestedModifier);
                                }
                            }
                        }
                    }
                }

                foreach ($item->bundles as $bundlesGroup) {
                    $addon_category = [];
                    $addon_category['name'] = $bundlesGroup['name'];
                    $addon_category['type'] = 'EXT';
                    if ($bundlesGroup['min'] == $bundlesGroup['max'] && $bundlesGroup['max'] != 0) {
                        $addon_category['type'] = 'SNG';
                        $addon_category['multi_select'] = true;
                    }
                    $addon_category['store_id'] = $store->id;
                    $addon_category['sku'] = $bundlesGroup['plu'];
                    $addon_category['minimum_amount'] = $bundlesGroup['min'];
                    $addon_category['maximum_amount'] = $bundlesGroup['max'];
                    $addon_cat = AddonCategory::updateOrCreate([
                        'sku' => $bundlesGroup['plu']
                    ], $addon_category);
                    $createdAddonCategories[$bundlesGroup['_id']] = $addon_cat;


                    if (count($bundlesGroup['nameTranslations']) > 0) {
                        foreach ($bundlesGroup['nameTranslations'] as $key => $value) {
                            $translation->$menu_id->$key[$bundlesGroup['name']] = $value;
                        }
                    }

                    if (count($bundlesGroup['subProducts']) > 0) {
                        foreach ($bundlesGroup['subProducts'] as $bundle) {
                            $itemBundles = $item->products[$bundle];
                            $addon = [];
                            $addon['addon_name'] = $itemBundles['name'];
                            $addon['price'] = floatval(floatval($itemBundles['price']) / 100);
                            $addon['addon_category_id'] = $addon_cat->id;
                            $addon['store_id'] = $store->id;
                            $addon['sku'] = $itemBundles['plu'];
                            $addon['kitchen_location_id'] = $main_kitchen;
                            $newAddon = Addon::updateOrCreate([
                                'sku' => $itemBundles['plu'],
                                'addon_category_id' => $addon['addon_category_id']
                            ], $addon);
                            $createdAddons[$bundle] = $newAddon;

                            if (count($itemBundles['nameTranslations']) > 0) {
                                foreach ($itemBundles['nameTranslations'] as $key => $value) {
                                    $translation->$menu_id->$key[$itemBundles['name']] = $value;
                                }
                            }

                            if (count($itemBundles['subProducts']) > 0) {
                                foreach ($itemBundles['subProducts'] as $nested_bundles) {
                                    $bundlesGroupItemName = $item->modifierGroups[$nested_bundles]['name'];
                                    $bundlesGroupItemId = AddonCategory::where('name', $modifierGroupItemName)->first();
                                    $itemNestedModifier = [
                                        "addon_category_id" => $bundlesGroupItemId['id'] ?? $addon_cat->id,
                                        "nested_addon_id" => $newAddon->id,
                                        "store_id" => $store->id
                                    ];
                                    $newNestedAddon = NestedAddon::updateOrCreate([
                                        "addon_category_id" => $bundlesGroupItemId['id'] ?? $addon_cat->id,
                                        "nested_addon_id" => $newAddon->id
                                    ], $itemNestedModifier);
                                }
                            }
                        }
                    }
                }

                foreach ($item->categories as $categories) {
                    $categories = (object)$categories;
                    $imageName = "";
                    $category_data = [];
                    if (!empty($categories->imageUrl)) {
                        $image = $categories->imageUrl;
                        $image_normal = Image::make($image)->fit(1920, 1080);
                        $resource = $image_normal->stream();
                        $file = pathinfo($categories->imageUrl);
                        $extension = $file['extension'];
                        $imageName = Str::random(20) . '.' . $extension;
                        // Storage::disk('public')->put("stores/category/images/" . $imageName, $Image);
                        Storage::disk('s3')->put("/catogories/$store->id/$imageName", $resource, 'public');
                        $category_data['image_url'] =  "catogories/$store->id/" . $imageName;
                    }
                    $category_data['name'] = $categories->name;
                    $category_data['store_id'] = $store->id;
                    $category_data['menu_id'] = $menu_id;
                    $category = Category::updateOrCreate([
                        'name' => $categories->name,
                        'store_id' => $store->id
                    ], $category_data);
                    $category_id = $category->id;
                    $createdCategories[$categories->_id] = $category;

                    if (count($categories->nameTranslations) > 0) {
                        foreach ($categories->nameTranslations as $key => $value) {
                            $translation->$menu_id->$key[$categories->name] = $value;
                        }
                    }

                    foreach ($categories->products as $products) {
                        $product_details = (object)$item->products[$products];
                        $product_data = [];
                        $product_data['name'] = $product_details->name;
                        $product_data['store_id'] = $store->id;
                        $product_data['category_id'] = $category_id;
                        $product_data['is_veg'] = 1;
                        $product_data['is_active'] = 1;
                        $product_data['description'] = $product_details->description ? $product_details->description : "";
                        $product_data['price'] = floatval(floatval($product_details->price) / 100);
                        $product_data['cooking_time'] = 10;
                        $product_data['is_recommended'] = 1;
                        $product_data['kitchen_location_id'] = $main_kitchen;
                        $product_data['sku'] = $product_details->plu;
                        if (!empty($product_details->imageUrl)) {
                            $image = $product_details->imageUrl;
                            $image_normal = Image::make($image)->fit(1920, 1080);
                            $resource = $image_normal->stream();
                            $file = pathinfo($product_details->imageUrl);
                            $extension = $file['extension'];
                            $imageName = Str::random(20) . '.' . $extension;
                            Storage::disk('s3')->put("/products/$store->id/$imageName", $resource, 'public');
                            $product_data['image_url'] =  "products/$store->id/" . $imageName;

                            // Storage::disk('public')->put("stores/product/images/" . $imageName, $Image);
                            // $product_data['image_url'] =  "storage/stores/product/images/" . $imageName;
                        }
                        $newProduct = Product::updateOrCreate([
                            'sku' => $product_details->plu,
                        ], $product_data);
                        $createdProducts[$products] = $newProduct;

                        if ($product_details->productTags != null) {
                            foreach ($product_details->productTags as $key => $allergen_id) {
                                DB::insert("insert into allergen_product (product_id, allergen_id) values ($newProduct->id, $allergen_id)");
                            }
                        }

                        if (count($product_details->nameTranslations) > 0) {
                            foreach ($product_details->nameTranslations as $key => $value) {
                                $translation->$menu_id->$key[$product_details->name] = $value;
                            }
                        }

                        if (count($product_details->subProducts) > 0) {
                            foreach ($product_details->subProducts as $categoryItems) {
                                $addon_category_item = [];
                                if (isset($createdAddonCategories[$categoryItems])) {
                                    $addon_category_item['addon_category_id'] = $createdAddonCategories[$categoryItems]->id;
                                    $addon_category_item['product_id'] = $newProduct->id;
                                    $addon_category_item['store_id'] = $store->id;
                                    AddonCategoryItem::updateOrCreate([
                                        'addon_category_id' => $createdAddonCategories[$categoryItems]->id,
                                        'product_id' => $newProduct->id
                                    ], $addon_category_item);
                                }
                            }
                        }
                    }
                }
            }

            $json = File::get(public_path("json/language.json"));
            $languages = json_decode($json);
            foreach ($translation as $men_id => $men) {
                foreach ($men as $lang => $obj) {
                    $trans = [
                        "language" => $languages->$lang,
                        "menu_id" => $men_id,
                        "data" => json_encode($obj),
                        "is_active" => 1
                    ];
                    MenuTranslation::updateOrCreate([
                        "language" => $languages->$lang,
                        "menu_id" => $men_id,
                    ], $trans);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json([
            'categories' => $createdCategories,
            'products' => $createdProducts,
            'addonCategories' => $createdAddonCategories,
            'addons' => $createdAddons
        ], 200);
    }

    public function createDeliverectOrder($order)
    {
        $store = $this->getUser($order->store_id);
        $get_token = $this->generateToken($store);

        $items = [];
        foreach ($order->orderDetails as $item) {
            $subItems = [];

            foreach ($item->OrderDetailsExtraAddon as $addon) {
                $nestedSubItems = [];
                foreach ($addon->OrderDetailsExtraParentAddon as $nestedAddon) {
                    $nestedSubItem = [
                        "plu" => (string) $nestedAddon->sku,
                        "name" => (string) $nestedAddon->addon_name,
                        "price" => (float) $this->toFixed($nestedAddon->addon_price, 2) * 100,
                        "quantity" => (int) $nestedAddon->addon_count,
                        "remark" => "",
                        "subItems" => []
                    ];
                    array_push($nestedSubItems, $nestedSubItem);
                }

                $subItems[] = [
                    "plu" => (string) $addon->sku,
                    "name" => (string) $addon->addon_name,
                    "price" => (float) $this->toFixed($addon->addon_price, 2) * 100,
                    "quantity" => (int) $addon->addon_count,
                    "remark" => "",
                    "subItems" => $nestedSubItems
                ];
            }

            $items[] = [
                "plu" => (string) $item->sku,
                "name" => (string) $item->name,
                "price" => (float) $this->toFixed($item->price, 2) * 100,
                "quantity" => (int) $item->quantity,
                "remark" => "",
                "subItems" => $subItems
            ];
        }
        $user = Customer::find($order->customer_id);

        $discountTotal = 0;
        if ($order->discount) {
            $discountTotal += (float) $this->toFixed($order->discount, 2) * 100;
        }
        if ($order->coupon) {
            $discountTotal += (float) $this->toFixed($order->coupon, 2) * 100;
        }


        $array = [
            "channelOrderId" => (string) $order->order_unique_id,
            "channelOrderDisplayId" => (string) $order->id,
            "channelLinkId" => $store->deliverect_channel_link_id,
            "by" => "",
            "orderType" => $order->table_no ? 3 : 1,
            "channel" => 10000,
            "pickupTime" => now()->toIso8601ZuluString(),
            "estimatedPickupTime" => now()->toIso8601ZuluString(),
            "deliveryTime" => now()->toIso8601ZuluString(),
            "deliveryIsAsap" => true,
            "courier" => "restaurant",
            "customer" => [
                "name" => $user ? $user->first_name . ' ' . $user->last_name : "No Name Provided",
                "companyName" => "Deliverect",
                "phoneNumber" => $user->phone ?? "No Phone Provided",
                "email" =>  $user->email ?? "No Email Provided"
            ],
            "deliveryAddress" => [
                "street" => "The Krook",
                "streetNumber" => "4",
                "postalCode" => "9000",
                "city" => "Gent",
                "extraAddressInfo" => ""
            ],
            "orderIsAlreadyPaid" => (float) $order->is_paid,
            "payment" => [
                "amount" => (float) $this->toFixed($order->total, 2) * 100,
                "type" => 0
            ],
            "note" =>  $order->comments ?? null,
            "items" => $items,
            "decimalDigits" => 2,
            "numberOfCustomers" => 1,
            "deliveryCost" => 0,
            "serviceCharge" => $order->store_charge ? (float) $this->toFixed($order->store_charge, 2) * 100 : 0,
            "discountTotal" => -$discountTotal,
            // "tip" => 0
        ];

        $response = Http::withHeaders([
            'Authorization' => $get_token->token_type . ' ' . $get_token->access_token,
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json; charset=utf-8', // Get
        ])
            ->post(
                'https://api.staging.deliverect.com/appetizr/order/' .  $store->deliverect_channel_link_id,
                $array
            );

        if ($response->failed()) {
            Log::error((string) $response->getBody(), ["order_id" => $order->id]);
        }

        return $response;
    }

    public function updateSnooze(Request $request)
    {
        \Log::info(json_encode($request->all()));
        $deliverect_channel_link_id = $request->channelLinkId;
        $response = [];
        foreach ($request->operations as $i => $operation) {
            $action = $operation['action'];
            $response['results'][$i] = (object)[];
            $response['results'][$i]->action = $action;
            $response['results'][$i]->data = [];
            $item_array = [];
            if (in_array($action, array('snooze', 'unsnooze'))) {
                foreach ($operation['data']['items'] as $item) {
                    array_push($item_array, $item['plu']);
                }
                Product::whereIn('sku', $item_array)->update([
                    'is_active' => $action == 'snooze' ? 0 : 1
                ]);
                Addon::whereIn('sku', $item_array)->update([
                    'is_active' => $action == 'snooze' ? 0 : 1
                ]);
                AddonCategory::whereIn('sku', $item_array)->update([
                    'is_active' => $action == 'snooze' ? 0 : 1
                ]);

                $allSnoozedProducts = Product::where('is_active', 0)->pluck('sku')->toArray();
                $allSnoozedAddons = Addon::where('is_active', 0)->pluck('sku')->toArray();
                $allSnoozedAddonsCategory = AddonCategory::where('is_active', 0)->pluck('sku')->toArray();
                $allSnoozedItems = array_merge($allSnoozedProducts, $allSnoozedAddons, $allSnoozedAddonsCategory);

                $response['results'][$i]->data['locationId'] = $request->locationId;
                $response['results'][$i]->data['allSnoozedItems'] = $allSnoozedItems;
            }
        }
        return response()->json($response, 200);
    }

    public function updateBusyMode(Request $request)
    {
        $deliverect_channel_link_id = $request->channelLinkId;
        $status = $request->status;
        $store = Store::where('deliverect_channel_link_id', $deliverect_channel_link_id)->first();
        $store->update([
            'is_accept_order' => $status == 'ONLINE' ? 1 : 0
        ]);
        return response()->json([
            'status' => $store->is_accept_order == '1' ? 'ONLINE' : 'PAUSED'
        ], 200);
    }

    public function syncProduct(Request $request, $store_id)
    {
        try {
            $store = Store::where('id', $store_id)->with('products.addonItems')->with('categories.products.addonItems')->with('addons')->with('addonCategories.addons')->with('addonCategoryItems')->first();

            $categories = [];
            $products = [];
            // $modifierGroups = [];
            // $modifiers = [];

            foreach ($store->categories as $category) {
                $category_data = array();
                $category_data['posCategoryId'] = 'CAT-' . $category->id;
                $category_data['name'] = $category->name;
                // $category_data['nameTranslations'] = [];
                $category_data['description'] = $category->description;
                // $category_data['descriptionTranslations'] = [];
                // $category_data['availabilities'] = [];
                $category_data['imageUrl'] = $category->image_url;
                $category_data['products'] = [];
                foreach ($category->products as $product) {
                    $category_data['products'][] = $product->sku;
                }
                $category_data['menu'] = $category->menu_id;

                // $categories[] = $category_data;
                // $categories[$category_data['id']] = $category_data;
                array_push($categories, $category_data);
            }

            foreach ($store->products as $product) {
                $product_data = array();
                $product_data['posProductId'] = 'PRO-' . $product->id;
                $product_data['posCategoryIds'] = [$product->category_id];
                $product_data['productType'] = 1;
                $product_data['plu'] = $product->sku;
                $product_data['price'] = $product->price;
                $product_data['name'] = $product->name;
                $product_data['deliveryTax'] = (isset($product->deliveryTax) && floatval($product->deliveryTax)) ? $product->deliveryTax : 0;
                $product_data['subProducts'] = [];
                foreach ($product->addonItems as $addonItem) {
                    $product_data['subProducts'][] = $addonItem->sku;
                }
                $product_data['productTags'] = [];
                $product_data['imageUrl'] = $product->imageUrl;
                $product_data['description'] = $product->description;
                $product_data['max'] = $product->max;
                $product_data['min'] = $product->min;
                $product_data['channel'] = $product->channel;

                array_push($products, $product_data);
            }

            foreach ($store->addonCategories as $addon_category) {
                $modifierGroup = array();
                $modifierGroup['posProductId'] = 'MOD-GRP-' . $addon_category->id;
                $modifierGroup['productType'] = 3;
                $modifierGroup['plu'] = $addon_category->sku;
                $modifierGroup['name'] = $addon_category->name;
                $modifierGroup['subProducts'] = [];
                foreach ($addon_category->addons as $addon) {
                    $modifierGroup['subProducts'][] = $addon->sku;
                }
                $modifierGroup['channel'] = $addon_category->channel;

                // array_push($modifierGroups, $modifierGroup);
                array_push($products, $modifierGroup);
            }

            foreach ($store->addons as $addon) {

                $modifier = array();
                $modifier['posProductId'] = 'MOD-' . $addon->id;
                $modifier['productType'] = 2;
                $modifier['plu'] = $addon->sku;
                $modifier['price'] = $addon->price;
                $modifier['name'] = $addon->addon_name;
                $modifier['deliveryTax'] = $addon->deliveryTax;
                $modifier['imageUrl'] = $addon->imageUrl;
                $modifier['description'] = $addon->description;
                $modifier['max'] = $addon->max;
                $modifier['min'] = $addon->min;
                $modifier['channel'] = $addon->channel;

                // $modifiers[] = $modifier;
                // array_push($modifiers, $modifier);
                array_push($products, $modifier);
                // $modifiers[$modifier['id']] = $modifier;
            };


            // $data = [
            //     'accountId' => '607ff2ed4a13c7014606de3d',
            //     'locationId' => '607ff2fe4a13c7014606de77',
            //     'products' => [
            //         0 => [
            //                 'productType' => 1,
            //                 'plu' => 'PR03',
            //                 'price' => 900,
            //                 'name' => 'Cheese Lovers Pizza',
            //                 'posProductId' => 'INTERNAL-POS-ID-1',
            //                 'posCategoryIds' => 'INTERNAL-POS-CAT-2',
            //                 'imageUrl' => '',
            //                 'uniqueKey' => '',
            //                 'description' => 'Pizza made for cheese fanatics',
            //                 'deliveryTax' => 6000,
            //                 'takeawayTax' => 6000,
            //                 'productTags' => [
            //                     0 => 104,
            //                     1 => 108,
            //                     2 => 100,
            //             ],
            //         ],
            //         1 => [
            //             'productType' => 1,
            //             'plu' => 'PR04',
            //             'price' => 900,
            //             'name' => 'BBQ Chicken Pizza',
            //             'posProductId' => 'INTERNAL-POS-ID-2',
            //             'posCategoryIds' => 'INTERNAL-POS-CAT-2',
            //             'imageUrl' => '',
            //             'uniqueKey' => '',
            //             'description' => 'The best BBQ Chicken Pizza in town',
            //             'deliveryTax' => 6000,
            //             'takeawayTax' => 6000,
            //             'productTags' => [
            //                 0 => 104,
            //                 1 => 108,
            //                 2 => 100,
            //             ],
            //         ],
            //     ],
            //     'categories' => [
            //         0 => [
            //         'name' => 'Pizza',
            //         'posCategoryId' => 'INTERNAL-POS-CAT-2',
            //         'imageUrl' => '',
            //         ],
            //     ],
            // ];

            $data = [
                'accountId' => '607ff2ed4a13c7014606de3d',
                'locationId' => '607ff2fe4a13c7014606de77',
                'categories' => $categories,
                'products' => $products,
                // 'modifierGroups' => $modifierGroups,
                // 'modifiers' => $modifiers,
                // 'categories' => [],
                // 'products' => [],
                // 'modifierGroups' => [],
                // 'modifiers' => [],
                // 'snoozedProducts' => []
            ];
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
