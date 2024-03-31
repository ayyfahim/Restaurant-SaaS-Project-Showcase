<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChageOrderIdOnWaiterCalllsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waiter_calls', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('waiter_calls', function (Blueprint $table) {
        //     $table->dropForeign(['order_id']);
        //     // $table->string("order_id")->nullable()->after('table_name');
        // });
        // Schema::table('waiter_calls', function (Blueprint $table) {
        //     $table->dropForeign(['order_id']);
        //     $table->string("order_id")->nullable()->after('table_name')->change();
        // });
    }
}
