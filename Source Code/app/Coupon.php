<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $casts = [
        'accepted_products' => 'array',
        'excluded_products' => 'array',
        'accepted_categories' => 'array',
        'excluded_categories' => 'array',
        'expires_at' => 'datetime',
    ];

    protected $guarded = [];

    public function customers()
    {
        return $this->belongsToMany('App\Models\Customer', 'customer_coupon');
    }
}
