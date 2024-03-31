<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        // dd($order);
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        // dd($order);
        if ($order->paid_amount >= $order->total) {
            $order->is_paid = 1;
            $order->save();
        }

        if ($order->status == 2) {
            $order->accepted_at = now();
            $order->save();
        }

        if ($order->status == 3) {
            $order->canceled_at = now();
            $order->save();
        }

        if ($order->status == 4) {
            $order->completed_at = now();
            $order->save();
        }

        if ($order->status == 5) {
            $order->served_at = now();
            $order->save();
        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        // dd($order);
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        // dd($order);
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        // dd($order);
    }
}
