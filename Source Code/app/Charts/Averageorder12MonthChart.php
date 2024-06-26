<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use App\Models\Order;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class Averageorder12MonthChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $store_id = (int) $request->store_id;

        $cumulative_count = [];
        $average_count = [];
        $average_cumulative_addon_count = 0;

        $average = [];
        $cumulative = [];

        $addon_count = 0;
        $order_count = 0;

        for ($i = 0; $i < 12; $i++) {
            $days[] = Carbon::now()->subMonth(12 - $i)->format('M');

            $fromDate = Carbon::now()->subMonth(12 - $i)->startOfMonth()->toDateString();
            $tillDate = Carbon::now()->subMonth(12 - $i)->endOfMonth()->toDateString();

            $customer_orders = Order::with('orderDetails.OrderDetailsExtraAddon')
                ->where('store_id', '=', $store_id)
                ->whereBetween('created_at', [$fromDate, $tillDate])
                ->get()
                ->groupBy('customer_id');

            foreach ($customer_orders as $key => $orders) {
                $order_count = $orders->count();

                foreach ($orders as $key => $order) {
                    foreach ($order->orderDetails as $key => $order_detail) {
                        $addon_count += $order_detail->OrderDetailsExtraAddon->count();
                    }
                }
            }

            if (!$customer_orders->count() > 0) {
                $order_count = 1;
            }

            $average_addon_count = $addon_count / $order_count;
            $average_cumulative_addon_count += $average_addon_count;

            $cumulative_count[] = $average_cumulative_addon_count;
            $average_count[] = $average_addon_count;

            $cumulative[] = ceil(array_sum($cumulative_count) / count($cumulative_count));
            $average[] = ceil(array_sum($average_count) / count($average_count));
        }

        return Chartisan::build()
            ->labels($days)
            ->dataset('Cumulative', $cumulative)
            ->dataset('Average', $average);
    }
}
