<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreOpenHour extends Model
{
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
}
