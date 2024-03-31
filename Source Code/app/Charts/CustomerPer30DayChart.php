<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use App\Models\Order;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class CustomerPer30DayChart extends BaseChart
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
        $data_cumulative = 0;
        $data_average = 0;

        $average = [];
        $cumulative = [];

        for ($i = 0; $i < 30; $i++) {
            $data = 0;
            $days[] = Carbon::now()->subDays(30 - $i)->format('m-d');

            $customer_orders = Order::with('orderDetails.OrderDetailsExtraAddon')
                ->where('store_id', '=', $store_id)
                ->whereDate('created_at','>=', Carbon::now()->subDays(30 - $i)->format('Y-m-d'))
                ->get()
                ->groupBy('customer_id');

            $data = $customer_orders->count();

            $data_cumulative += $data;
            $data_average = $data;

            $cumulative_count[] = $data_cumulative;
            $average_count[] = $data_average;

            $cumulative[] = ceil(array_sum($cumulative_count) / count($cumulative_count));
            $average[] = ceil(array_sum($average_count) / count($average_count));
        }

        return Chartisan::build()
            ->labels($days)
            ->dataset('Cumulative', $cumulative)
            ->dataset('Average', $average);
    }
}
