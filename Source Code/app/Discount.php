<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded = [];

    public function getTypeAttribute()
    {
        if ($this->discount_type == 1) {
            return "Fixed";
        } else {
            return "Percentage";
        }
    }

    public function getPriceAttribute()
    {
        if ($this->discount_type == 1) {
            return $this->discount_price_fixed;
        } else {
            return "{$this->discount_price_percentage}%";
        }
    }

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function time_restrictions()
    {
        return $this->morphToMany('App\TimeRestriction', 'restrictionable');
    }
}
