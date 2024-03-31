<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NestedAddon extends Model
{
    //
    protected $guarded = [];

    public function addons()
    {
        return $this->hasMany('App\Models\Addon', 'addon_id');
    }

    public function nested_addons()
    {
        return $this->hasMany('App\Models\AddonCategory', 'nested_addon_id');
    }

    public function addon_category()
    {
        return $this->belongsTo('App\Models\AddonCategory', 'addon_category_id');
    }

    public function store()
    {
        return $this->hasOne('App\Models\Store', 'store_id');
    }
}
