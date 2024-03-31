<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusTimesToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('accepted_at', $precision = 0)->nullable();
            $table->timestamp('canceled_at', $precision = 0)->nullable();
            $table->timestamp('completed_at', $precision = 0)->nullable();
            $table->timestamp('served_at', $precision = 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('accepted_at');
            $table->dropColumn('canceled_at');
            $table->dropColumn('completed_at');
            $table->dropColumn('served_at');
        });
    }
}
