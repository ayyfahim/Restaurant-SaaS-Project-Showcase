<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('store_name');
            $table->text('logo_url')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('phone');
            $table->bigInteger('theme_id')->nullable();
            $table->date('subscription_end_date');
            $table->boolean('is_visible')->default(1);
            $table->bigInteger('add_by')->nullable();
            $table->text('view_id');
            $table->rememberToken();
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
        Schema::dropIfExists('stores');
    }
}
