<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($category) {
            $category->update([
                'index_number' => $category->createIndexNumber()
            ]);
        });

        static::updated(function ($category) {
            if ($category->isDirty('kitchen_location_id')) {
                if ($category->kitchen_location_id) {
                    $category->load('products');
                    foreach ($category->products as $product) {
                        if (!$product->kitchen_location_id) {
                            $product->update([
                                'kitchen_location_id' => $category->kitchen_location_id
                            ]);
                        }
                    }
                }
            }
        });
    }

    /**
     * Get the comments for the blog post.
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id');
    }

    public function kitchen_location()
    {
        return $this->belongsTo('App\Kitchen', 'kitchen_location_id');
    }

    public function createIndexNumber()
    {
        $categories = Category::where('store_id', $this->store_id)->get();

        if (!$categories) {
            return null;
        }

        $index_numbers = $categories->pluck('index_number')->toArray();

        $getMaxIndexNumber =  max($index_numbers);

        return $getMaxIndexNumber + 1;
    }

    public function checkIndexNumber($number)
    {
        if ($number == $this->index_number) {
            return true;
        }

        $categories = Category::where('store_id', $this->store_id)->get();

        if (!$categories) {
            return null;
        }

        $index_numbers = $categories->pluck('index_number');

        if ($index_numbers->contains($number)) {
            return false;
        }

        return true;
    }
}
