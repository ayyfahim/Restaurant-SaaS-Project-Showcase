<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class RestaurantAnalyticsChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $payment_last_7_days = [];
        $cumulativePayments = 0;
        for ($i = 0; $i < 7; $i++) {
            $days[] = Carbon::now()->subDays(7 - $i)->format('l');
            $cumulativePayments += \App\Payment::whereDate('created_at','>=', Carbon::now()->subDays(7 - $i)->format('Y-m-d'))->count();
            $payment_last_7_days[] = $cumulativePayments;

            $average[] = ceil(array_sum($payment_last_7_days) / count($payment_last_7_days));
        }

        return Chartisan::build()
            ->labels($days)
            ->dataset('Average bill per customer', $average);
    }
}
