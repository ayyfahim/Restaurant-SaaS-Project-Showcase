<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreLocationFieldsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('google_map_address')->default('Sydney Australia');
            $table->string('address_latitude')->default(-33.8688);
            $table->string('address_longitude')->default(151.2195);
            $table->integer('location_required')->default(1);
            $table->integer('order_range')->default(1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('google_map_address');
            $table->dropColumn('address_latitude');
            $table->dropColumn('address_longitude');
            $table->dropColumn('location_required');
            $table->dropColumn('order_range');
        });
    }
}
