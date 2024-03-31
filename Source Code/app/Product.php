<?php

namespace App;

use Carbon\Carbon;
use App\Models\Addon;
use Cmixin\BusinessTime;
use App\Models\AddonCategory;
use App\Models\AddonCategoryItem;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $appends = ['is_availiable', 'is_discountable'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($product) {
            $product->update([
                'index_number' => $product->createIndexNumber()
            ]);

            if (!$product->kitchen_location_id) {
                if ($product->category_id) {
                    $category = Category::find($product->category_id);
                    if ($category->kitchen_location_id) {
                        $product->update([
                            'kitchen_location_id' => $category->kitchen_location_id
                        ]);
                    }
                }
            }
        });
    }

    public function getIsAvailiableAttribute(){
        if ($this->time_restrictions->count() > 0) {
            $start_time_end_time = Carbon::parse($this->time_restrictions->first()->data['start_time'])->format('H:i') . "-" . Carbon::parse($this->time_restrictions->first()->data['end_time'])->format('H:i');

            BusinessTime::enable(Carbon::class, [
                'monday' => [$start_time_end_time],
                'tuesday' =>
                [$start_time_end_time],
                'wednesday' =>
                [$start_time_end_time],
                'thursday' =>
                [$start_time_end_time],
                'friday' =>
                [$start_time_end_time],
                'saturday' =>
                [$start_time_end_time],
                'sunday' =>
                [$start_time_end_time],
            ]);

            return Carbon::now()->isOpen();
        }
        return true;
    }

    public function getIsDiscountableAttribute(){

        // Product has no time restriction
        if ($this->discounts->count() > 0 && $this->discounts->first()->time_restrictions->count() == 0) {
            return true;
        }

        // Product has time restriction
        if ($this->discounts->count() > 0 && $this->discounts->first()->time_restrictions->count() > 0) {
            $start_time_end_time = Carbon::parse($this->discounts->first()->time_restrictions->first()->data['start_time'])->format('H:i') . "-" . Carbon::parse($this->discounts->first()->time_restrictions->first()->data['end_time'])->format('H:i');
            BusinessTime::enable(Carbon::class, [
                'monday' => [$start_time_end_time],
                'tuesday' =>
                [$start_time_end_time],
                'wednesday' =>
                [$start_time_end_time],
                'thursday' =>
                [$start_time_end_time],
                'friday' =>
                [$start_time_end_time],
                'saturday' =>
                [$start_time_end_time],
                'sunday' =>
                [$start_time_end_time],
            ]);

            return Carbon::now()->isOpen();
        }
        
        return false;
    }

    public function allergens()
    {
        return $this->belongsToMany('App\Allergen');
    }

    public function addonCategories()
    {
        return $this->hasMany(AddonCategory::class);
    }

    public function addonItems()
    {
        return $this->hasMany(AddonCategoryItem::class);
    }

    public function categories()
    {
        return $this->belongsTo(AddonCategory::class);
    }

    public function kitchen_location()
    {
        return $this->belongsTo('App\Kitchen', 'kitchen_location_id');
    }

    public function time_restrictions()
    {
        return $this->morphToMany('App\TimeRestriction', 'restrictionable');
    }

    public function discounts()
    {
        return $this->belongsToMany('App\Discount', 'discount_product');
    }

    public function createIndexNumber()
    {
        $category = Category::find($this->category_id);

        if (!$category) {
            return null;
        }

        $products = $category->products->sortBy('id');

        $index_numbers = $products->pluck('index_number')->toArray();

        // return $index_numbers;

        $getMaxIndexNumber =  max($index_numbers);

        return $getMaxIndexNumber + 1;
    }

    public function checkIndexNumber($number, int $category_id = null)
    {
        if ($this->type == 2) {
            $products = Product::where('type', 2)->where('store_id', $this->store_id)->get();

            if (!$products) {
                return null;
            }

            $index_numbers = $products->pluck('index_number');

            if ($index_numbers->contains($number)) {
                return false;
            }

            return true;

        }

        if ($this->category_id == $category_id) {
            if ($number == $this->index_number) {
                return true;
            }
        }

        $category = Category::find($category_id);

        if (!$category) {
            return null;
        }

        $products = $category->products->sortBy('id');

        $index_numbers = $products->pluck('index_number');

        if ($index_numbers->contains($number)) {
            return false;
        }

        return true;

        // return $index_numbers;

        // $getMaxIndexNumber =  max($index_numbers);

        // return $getMaxIndexNumber + 1;
    }

    public function get_product_is_availiable()
    {
        $start_time_end_time = Carbon::parse($this->time_restrictions->first()->data['start_time'])->format('H:i') . "-" . Carbon::parse($this->time_restrictions->first()->data['end_time'])->format('H:i');
        BusinessTime::enable(Carbon::class, [
            'monday' => [$start_time_end_time],
            'tuesday' =>
            [$start_time_end_time],
            'wednesday' =>
            [$start_time_end_time],
            'thursday' =>
            [$start_time_end_time],
            'friday' =>
            [$start_time_end_time],
            'saturday' =>
            [$start_time_end_time],
            'sunday' =>
            [$start_time_end_time],
        ]);

        return Carbon::now()->isOpen();
    }

    public function get_product_discount()
    {
        $start_time_end_time = Carbon::parse($this->discounts->first()->time_restrictions->first()->data['start_time'])->format('H:i') . "-" . Carbon::parse($this->discounts->first()->time_restrictions->first()->data['end_time'])->format('H:i');
        BusinessTime::enable(Carbon::class, [
            'monday' => [$start_time_end_time],
            'tuesday' =>
            [$start_time_end_time],
            'wednesday' =>
            [$start_time_end_time],
            'thursday' =>
            [$start_time_end_time],
            'friday' =>
            [$start_time_end_time],
            'saturday' =>
            [$start_time_end_time],
            'sunday' =>
            [$start_time_end_time],
        ]);

        return Carbon::now()->isOpen();
    }
}
