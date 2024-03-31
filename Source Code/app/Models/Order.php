<?php

namespace App\Models;

use App\Notifications\OrderCancelNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     'paid_amount' => 'float',
    //     'total' => 'float',
    // ];

    // public function getTotalAttribute()
    // {
    //     return (float) $this->attributes['total'];
    // }

    // public function getPaidAmountAttribute()
    // {
    //     return (float) $this->attributes['paid_amount'];
    // }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->paid_amount >= $order->total) {
                if (!$order->is_paid == 1) {
                    $order->is_paid = 1;
                    $order->save();
                }
            }

            if ($order->isDirty('status')) {
                if ($order->status == 2) {
                    if (!$order->accepted_at) {
                        $order->accepted_at = \now();
                        $order->save();
                    }
                }

                if ($order->status == 3) {
                    if (!$order->canceled_at) {
                        $order->canceled_at = \now();

                        $order->customer->notify(new OrderCancelNotification($order));

                        $order->save();
                    }
                }

                if ($order->status == 4) {
                    if (!$order->completed_at) {
                        $order->completed_at = \now();
                        $order->save();
                    }
                }

                if ($order->status == 5) {
                    if (!$order->served_at) {
                        $order->served_at = \now();
                        $order->save();
                    }
                }
            }
        });
    }

    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetails');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function table()
    {
        return $this->belongsTo('App\Models\Table', 'table_no');
    }

    public function total_orders($phone)
    {
        return Order::all()->where('customer_phone', '=', $phone)->sum('total');
    }

    public function total($phone)
    {
        return Order::all()->where('customer_phone', '=', $phone)->count();
    }

    public function waiter_orders()
    {
        return $this->hasMany('App\WaiterOrder');
    }

    // public function getUnpaidOrdersAttribute()
    // {
    //     $total_amount = $this->total;
    //     $paid_amount = $this->paid_amount;

    //     return $this->where('total', '>=', $paid_amount);
    // }
}
