<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    protected $table = "menu_translations";
    protected $fillable = ["language","menu_id","data","is_active"];

    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id');
    }
}
