<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonCategoryItem extends Model
{
    protected $guarded = [];
    protected $table = "addon_category_items";

    public function categories()
    {
        return $this->hasMany(AddonCategory::class, 'id', 'addon_category_id');
    }
}
