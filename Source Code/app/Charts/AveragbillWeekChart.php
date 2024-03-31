<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;

class AveragbillWeekChart extends BaseChart
{
    // public function __construct()
    // {
    //     $knownDate = Carbon::create(2021, 4, 29);
    //     Carbon::setTestNow($knownDate);
    // }

    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $now = Carbon::now();
        $weeks = $now->weekOfMonth;
        // dd($now->for);
        $dt = Carbon::create($now->year, $now->month, $now->firstOfMonth()->format('d'), 0, 0, 0);
        $dt->startOfWeek();

        /** Creating from, to array Date $dt->format('m') */
        $dt->toTimeString();

        for ($i = 0; $i < $weeks; $i++) {
            $from = $dt->format('m-d');
            $to = $dt->addWeek(1);
            if (!$to->isFuture()) {
                $labels[] = $from . ' - ' . $to->format('m-d');
            }
        }

        $payment_last_week = [];
        $cumulativePayments = 0;

        $now = Carbon::now();
        $weeks = $now->weekOfMonth - 1;
        $dt = Carbon::create($now->year, $now->month, $now->firstOfMonth()->format('d'), 0, 0, 0);
        $dt->startOfWeek();

        for ($i = 0; $i < $weeks; $i++) {

            $fromData = $dt->format('Y-m-d');
            $toData = $dt->addWeek(1)->format('Y-m-d');

            $cumulativePayments += \App\Payment::whereBetween('created_at', array($fromData, $toData))->count();
            $payment_last_week[] = $cumulativePayments;

            // $average[$fromData . ' - ' . $toData] = ceil(array_sum($payment_last_week) / count($payment_last_week));
            $average[] = ceil(array_sum($payment_last_week) / count($payment_last_week));
        }

        return Chartisan::build()
            ->labels($labels ?? ['No week found'])
            ->dataset('Average bill per week', $average ?? ['No average found']);
    }
}
