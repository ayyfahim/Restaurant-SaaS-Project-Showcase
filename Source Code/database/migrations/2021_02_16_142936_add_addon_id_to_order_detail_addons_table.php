<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddonIdToOrderDetailAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_detail_addons', function (Blueprint $table) {
            $table->unsignedBigInteger('addon_id')->after('order_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_detail_addons', function (Blueprint $table) {
            $table->dropColumn('addon_id');
        });
    }
}
