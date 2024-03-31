<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNestedAddonFieldToOrderDetailAddons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_detail_addons', function (Blueprint $table) {
            $table->unsignedBigInteger("parent_addon_id")->after('addon_id')->nullable();
            $table->foreign('parent_addon_id')->references('id')->on('order_detail_addons')->onDelete('cascade');

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
            $table->dropForeign('order_detail_addons_parent_addon_id_foreign');
        });
        Schema::table('order_detail_addons', function (Blueprint $table) {
            $table->dropColumn('parent_addon_id');
        });
    }
}
