<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class AveragbillDayChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        // dd(Carbon::now()->subDays(7 - 0)->format('Y-m-d'));

        $payment_last_7_days_cumulative = [];
        $payment_last_7_days_average = [];
        $cumulativePayments = 0;
        $averagePayments = 0;

        $average = [];
        $cumulative = [];

        for ($i = 0; $i < 7; $i++) {
            $days[] = Carbon::now()->subDays(7 - $i)->format('l');
            $cumulativePayments += \App\Payment::whereDate('created_at','>=', Carbon::now()->subDays(7 - $i)->format('Y-m-d'))->count();
            $payment_last_7_days_cumulative[] = $cumulativePayments;

            $cumulative[] = ceil(array_sum($payment_last_7_days_cumulative) / count($payment_last_7_days_cumulative));
        }

        for ($i = 0; $i < 7; $i++) {
            $averagePayments = \App\Payment::whereDate('created_at','>=', Carbon::now()->subDays(7 - $i)->format('Y-m-d'))->count();
            $payment_last_7_days_average[] = $averagePayments;

            $average[] = ceil(array_sum($payment_last_7_days_average) / count($payment_last_7_days_average));
        }

        return Chartisan::build()
            ->labels($days)
            ->dataset('Cumulative', $cumulative)
            ->dataset('Average', $average);
    }
}
