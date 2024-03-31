<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestrictionablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restrictionables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_restriction_id');
            $table->unsignedBigInteger('restrictionable_id')->nullable();
            $table->string('restrictionable_type')->nullable();
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
        Schema::dropIfExists('restrictionables');
    }
}
