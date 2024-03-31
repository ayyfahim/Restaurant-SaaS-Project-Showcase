<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNestedAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nested_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addon_category_id');
            $table->unsignedBigInteger('nested_addon_id');
            $table->unsignedBigInteger('store_id');
            $table->timestamps();

            $table->foreign('addon_category_id')->references('id')->on('addon_categories')->onDelete('cascade');
            $table->foreign('nested_addon_id')->references('id')->on('addons')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nested_addons');
    }
}
