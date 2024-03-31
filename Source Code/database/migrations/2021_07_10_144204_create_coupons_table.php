<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
    
                $table->string('code', 32);
    
                $table->integer('percantage_amount')->nullable();
                $table->float('fixed_amount')->nullable();
    
                $table->float('minimum_spend')->default(0)->nullable();
                $table->float('maximum_spend')->default(0)->nullable();
    
                $table->json('accepted_products')->nullable();
                $table->json('excluded_products')->nullable();
                $table->json('accepted_categories')->nullable();
                $table->json('excluded_categories')->nullable();
    
                $table->integer('limit_per_user')->default(0)->nullable();
    
                $table->timestamp('expires_at')->nullable();
    
                $table->foreignId('store_id')->constrained()->onDelete('cascade');
    
                $table->timestamps();
            });
        } catch (\Throwable $th) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
    
                $table->string('code', 32);
    
                $table->integer('percantage_amount')->nullable();
                $table->float('fixed_amount')->nullable();
    
                $table->float('minimum_spend')->default(0)->nullable();
                $table->float('maximum_spend')->default(0)->nullable();
    
                $table->text('accepted_products')->nullable();
                $table->text('excluded_products')->nullable();
                $table->text('accepted_categories')->nullable();
                $table->text('excluded_categories')->nullable();
    
                $table->integer('limit_per_user')->default(0)->nullable();
    
                $table->timestamp('expires_at')->nullable();
    
                $table->foreignId('store_id')->constrained()->onDelete('cascade');
    
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
