<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesToAddonCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addon_categories', function (Blueprint $table) {
            $table->integer('minimum_amount')->after('store_id')->nullable();
            $table->integer('maximum_amount')->after('minimum_amount')->nullable();
            $table->boolean('multi_select')->after('maximum_amount')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addon_categories', function (Blueprint $table) {
            $table->dropColumn('minimum_amount');
            $table->dropColumn('maximum_amount');
            $table->dropColumn('multi_select');
        });
    }
}
