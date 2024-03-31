<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $guarded = [];

    public function bank_detailable()
    {
        return $this->morphTo('detailable_type', 'detailable_id');
    }
}
