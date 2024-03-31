<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexNumberToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('kitchen_location_id')->after('store_id')->nullable();
            $table->foreign('kitchen_location_id')->references('id')->on('kitchens')->onDelete('cascade');
            $table->bigInteger('index_number')->after('kitchen_location_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['kitchen_location_id']);
            $table->dropColumn('index_number');
        });
    }
}
