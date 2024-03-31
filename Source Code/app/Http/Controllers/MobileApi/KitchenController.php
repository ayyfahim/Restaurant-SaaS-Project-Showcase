<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Kitchen;

class KitchenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:kitchenApi');
    }

    public function dashboard($store_id, $kitchen_id){

        $kitchen = Kitchen::find($kitchen_id);
        if($kitchen->is_main != 1){
            $id = $kitchen_id;
            $tables = Table::with([
                'kitchen_orders.orderDetails.OrderDetailsExtraAddon' => function ($query) use ($id) {
                    $query->where('kitchen_location_id', $id);
                    $query->where('status', 0);
                },
            ])->where('store_id', $store_id)->get()->SortByDesc('id')->toArray();

            foreach ($tables as $table_key => $table) {
                foreach ($table['kitchen_orders'] as $order_key => $order) {
                    foreach ($order['order_details'] as $order_detail_key => $detail) {
                        if ($detail['kitchen_location_id'] != $id) {
                            if ($detail['order_details_extra_addon']) {
                                foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
                                    if ($addon['kitchen_location_id'] == $id) {
                                        unset($table['kitchen_orders'][$order_key]);
                                        $table['kitchen_orders'] = array_values($table['kitchen_orders']);
                                    }
                                }
                            } else {
                                unset($tables[$table_key]['kitchen_orders'][$order_key]);
                                $tables[$table_key]['kitchen_orders'] = array_values($tables[$table_key]['kitchen_orders']);
                            }
                        }
                        if ($detail['status'] == 1) {
                            if ($detail['order_details_extra_addon']) {
                                foreach ($detail['order_details_extra_addon'] as $addon_key => $addon) {
                                    if ($addon['status'] == 1) {
                                        unset($table['kitchen_orders'][$order_key]);
                                        $table['kitchen_orders'] = array_values($table['kitchen_orders']);
                                    }
                                }
                            } else {
                                unset($tables[$table_key]['kitchen_orders'][$order_key]);
                                $tables[$table_key]['kitchen_orders'] = array_values($tables[$table_key]['kitchen_orders']);
                            }
                        }
                    }
                }
            }
        }else{
            $tables = Table::with('kitchen_orders.orderDetails.OrderDetailsExtraAddon')->where('store_id', $store_id)->get()->SortByDesc('id')->toArray();
        }

        $tables = array_values($tables);
        return $tables;
    }
}
