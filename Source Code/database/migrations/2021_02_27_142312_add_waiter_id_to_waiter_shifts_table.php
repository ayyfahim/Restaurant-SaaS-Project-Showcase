<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWaiterIdToWaiterShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waiter_shifts', function (Blueprint $table) {
            $table->unsignedBigInteger('waiter_id')->after('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waiter_shifts', function (Blueprint $table) {
            $table->dropColumn('waiter_id');
        });
    }
}
