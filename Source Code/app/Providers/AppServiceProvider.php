<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder as QueryBuilder;
use ConsoleTVs\Charts\Registrar as Charts;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Charts $charts)
    {
        Builder::defaultStringLength(191);

        QueryBuilder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (QueryBuilder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                }
            });

            return $this;
        });

        $charts->register([
            \App\Charts\AveragbillDayChart::class,
            \App\Charts\Averagbill30DayChart::class,
            \App\Charts\Averagbill12MonthChart::class,
            \App\Charts\Averageorder7DayChart::class,
            \App\Charts\Averageorder30DayChart::class,
            \App\Charts\Averageorder12MonthChart::class,
            \App\Charts\OrderPerCustomer7dayChart::class,
            \App\Charts\OrderPerCustomer30dayChart::class,
            \App\Charts\OrderPerCustomer12monthChart::class,
            \App\Charts\CustomerPer7DayChart::class,
            \App\Charts\CustomerPer30DayChart::class,
            \App\Charts\CustomerPer12MonthChart::class,
        ]);
    }
}
