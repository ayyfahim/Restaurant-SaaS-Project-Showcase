<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('adyen_card_number', 'card_number');
            $table->dropColumn('adyen_shopper_reference');
            $table->decimal('amount')->nullable()->change();
            $table->string('limonetik_order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('card_number', 'adyen_card_number');
            $table->string('adyen_shopper_reference')->nullable();
            $table->integer('amount')->nullable()->change();
            $table->dropColumn('limonetik_order_id');
        });
    }
}
