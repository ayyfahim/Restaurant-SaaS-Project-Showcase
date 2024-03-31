<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesToOrdersAndPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tips');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal("tips")->nullable()->default('0')->after('amount');
            $table->text('order_ids')->nullable()->after('card_number');

            $table->dropColumn('order_id');
            $table->dropColumn('order_unique_id');
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
            $table->decimal("tips")->nullable()->default('0');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->string('order_id')->nullable();
            $table->string('order_unique_id')->nullable();

            $table->dropColumn('tips');
            $table->dropColumn('order_ids');
        });
    }
}
