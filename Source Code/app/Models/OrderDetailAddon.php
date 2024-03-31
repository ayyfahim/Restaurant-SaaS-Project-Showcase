<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailAddon extends Model
{
    protected $guarded = [];

    public function OrderDetailsExtraParentAddon(){
        return $this->hasMany('App\Models\OrderDetailAddon','parent_addon_id','id');
    }

}
