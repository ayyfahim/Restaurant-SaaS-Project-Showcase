<?php

namespace App\Models;

use App\Models\Table as ModelsTable;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $guarded = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($table) {
            if (!$table->table_number) {
                // dd($table->table_number);
                $table->table_number = ModelsTable::all()->where('store_id', '=', $table->store_id)->count();
                $table->save();
            }
        });
    }

    public function total_order_count($table_number)
    {
        return Order::all()->where('table_no', '=', $table_number)->where('status', '=', 4)->count();
    }

    public function total_order_sum($table_number)
    {
        return Order::all()->where('table_no', '=', $table_number)->where('status', '=', 4)->sum('total');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'table_no');
    }

    public function waiters()
    {
        return $this->belongsToMany('App\Waiter', 'waiter_table');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    public function unpaid_orders()
    {
        return $this->orders()->where('is_paid', 0);
    }

    public function kitchen_orders()
    {
        return $this->orders()->where('status', '<=', '2');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'table_id');
    }
}
