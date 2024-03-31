<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
    ];

    public function addon_categories($addon_category_id)
    {
        return AddonCategory::all()->where('id', '=', $addon_category_id);
    }

    public function kitchen_location()
    {
        return $this->belongsTo('App\Kitchen', 'kitchen_location_id');
    }

    public function nested_addons()
    {
        return $this->hasMany('App\NestedAddon', 'nested_addon_id');
    }
}
