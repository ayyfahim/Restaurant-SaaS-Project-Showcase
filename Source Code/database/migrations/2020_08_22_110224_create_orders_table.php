<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("order_unique_id");
            $table->unsignedBigInteger("store_id");
            $table->string("table_no")->nullable();
            $table->string("customer_name")->nullable();
            $table->string("customer_phone")->nullable();
            $table->decimal("sub_total")->nullable();
            $table->decimal("discount")->nullable();
            $table->decimal("tax")->nullable();
            $table->decimal("store_charge")->nullable();
            $table->decimal("total")->nullable();
            $table->bigInteger("status")->default(1);
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
