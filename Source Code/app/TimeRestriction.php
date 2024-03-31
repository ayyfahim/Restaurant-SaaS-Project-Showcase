<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeRestriction extends Model
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

    // public function restrictionable()
    // {
    //     return $this->morphTo('restrictionable', 'restrictionable_type', 'restrictionable_id');
    // }

    public function products()
    {
        return $this->morphedByMany('App\Product', 'restrictionable', 'restrictionables');
    }
}
