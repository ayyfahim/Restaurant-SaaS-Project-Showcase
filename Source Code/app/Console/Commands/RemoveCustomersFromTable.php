<?php

namespace App\Console\Commands;

use App\Models\Table;
use Illuminate\Console\Command;

class RemoveCustomersFromTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:remove_customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Customers from Table if all orders are paid within 1 Hour.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $log = [];
        foreach (Table::with(['unpaid_orders', 'orders', 'customers'])->get() as $table) {
            if (!$table->unpaid_orders->count() > 0) {
                // dd($selectedTable->orders->where('paid_at' , '>' , now()->subHours(1))->count() > 0);
                if (!$table->orders->where('paid_at' , '>' , now()->subHours(1))->count() > 0) {
                    foreach ($table->customers as $customer) {
                        if ($customer->table_joined_at->isCurrentHour()) {
                            // $log['isCurrentHour'] = $customer->full_name;
                           return;
                        } else {
                            // $log['notCurrentHour'] = $customer->full_name;
                            $customer->update([
                                'table_id' => null,
                                'table_joined_at' => null,
                            ]);
                        }
                        
                    }
                }
            }
        }

        // dd($log);
    }
}
