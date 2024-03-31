<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaiterOrder extends Model
{
    protected $guarded = [];

    public function waiter()
    {
        return $this->belongsTo('App\Waiter');
    }
}
