<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddonCategoryTableAddSkuField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addon_categories', function (Blueprint $table) {
            //
            $table->string('sku', 191)->after('store_id')->nullable()->collation('utf8mb4_unicode_ci')->unique();
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
            //
            $table->dropColumn('sku');
        });
    }
}
