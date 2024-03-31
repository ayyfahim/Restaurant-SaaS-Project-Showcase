<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKitchenLocationIdToOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('kitchen_location_id')->after('order_id')->default(null);
            $table->foreign('kitchen_location_id')->references('id')->on('kitchens')->onDelete('cascade');
            $table->integer('status')->after('kitchen_location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['kitchen_location_id']);
            $table->dropColumn('status');
        });
    }
}
