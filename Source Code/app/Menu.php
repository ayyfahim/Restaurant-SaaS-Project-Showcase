<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public function categories()
    {
        return $this->hasMany('App\Category', 'menu_id');
    }

    public function translations()
    {
        return $this->hasMany('App\MenuTranslation', 'menu_id');
    }
}
