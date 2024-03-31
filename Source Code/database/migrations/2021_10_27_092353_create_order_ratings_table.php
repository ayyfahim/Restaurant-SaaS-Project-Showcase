<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->comment('oeder id');
            $table->enum('reason',['1','2','3','4','5','6','7','8','9','10000'])->nullable()->comment('Rating Reasons');
            $table->integer('rating')->comment('Rating given by user');
            $table->string('comment')->comment('Review write by user')->nullable();
            $table->enum('order_type',['1','2'])->comment('1 is Restaurant || 2 is Delivery');
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
        Schema::dropIfExists('order_ratings');
    }
}
