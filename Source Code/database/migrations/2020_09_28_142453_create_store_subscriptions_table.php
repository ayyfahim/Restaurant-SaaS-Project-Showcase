<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->decimal("price");
            $table->text("description");
            $table->bigInteger('days');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_one_time')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_subscriptions');
    }
}
