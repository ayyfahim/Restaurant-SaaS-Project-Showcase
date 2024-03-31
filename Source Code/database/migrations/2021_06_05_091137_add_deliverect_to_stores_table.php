<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliverectToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('deliverect_api_key')->nullable()->after('tax');
            $table->string('deliverect_api_secret_key')->nullable()->after('deliverect_api_key');
            $table->string('deliverect_webhook_url')->nullable()->after('deliverect_api_secret_key');
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
            $table->removeColumn('deliverect_api_key');
            $table->removeColumn('deliverect_api_secret_key');
            $table->removeColumn('deliverect_webhook_url');
        });
    }
}
