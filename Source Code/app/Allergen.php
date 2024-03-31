<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
