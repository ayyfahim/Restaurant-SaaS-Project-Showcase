<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_restrictions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('data',);
            // $table->unsignedBigInteger('restrictionable_id')->nullable();
            // $table->string('restrictionable_type')->nullable();
            // $table->unsignedBigInteger('store_id');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('time_restrictions');
    }
}
