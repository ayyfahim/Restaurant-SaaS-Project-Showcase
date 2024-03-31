<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class Averagbill12MonthChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $payment_last_12_month_cumulative = [];
        $payment_last_12_month_average = [];
        $cumulativePayments = 0;
        $averagePayments = 0;

        $average = [];
        $cumulative = [];

        for ($i = 0; $i < 12; $i++) {
            $months[] = Carbon::now()->subMonth(12 - $i)->format('M');

            $fromDate = Carbon::now()->subMonth(12 - $i)->startOfMonth()->toDateString();
            $tillDate = Carbon::now()->subMonth(12 - $i)->endOfMonth()->toDateString();

            $cumulativePayments += \App\Payment::whereBetween('created_at', [$fromDate, $tillDate])->count();
            $payment_last_12_month_cumulative[] = $cumulativePayments;

            $cumulative[] = ceil(array_sum($payment_last_12_month_cumulative) / count($payment_last_12_month_cumulative));
        }

        for ($i = 0; $i < 12; $i++) {

            $fromDate = Carbon::now()->subMonth(12 - $i)->startOfMonth()->toDateString();
            $tillDate = Carbon::now()->subMonth(12 - $i)->endOfMonth()->toDateString();

            $averagePayments = \App\Payment::whereBetween('created_at', [$fromDate, $tillDate])->count();
            $payment_last_12_month_average[] = $averagePayments;

            $average[] = ceil(array_sum($payment_last_12_month_average) / count($payment_last_12_month_average));
        }

        return Chartisan::build()
            ->labels($months)
            ->dataset('Average', $average)
            ->dataset('Cumulative', $cumulative);
    }
}
