<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $guarded = [];
    protected $casts = [
        'data' => 'array',
    ];
}
