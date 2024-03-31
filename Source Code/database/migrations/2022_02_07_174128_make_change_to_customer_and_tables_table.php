<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeChangeToCustomerAndTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('table_id')
                    ->nullable()
                    ->constrained();

            $table->timestamp('table_joined_at', $precision = 0)->nullable();
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('old_customers');
            $table->dropColumn('new_customers');
            $table->dropColumn('is_fetchable');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('paid_at', $precision = 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::enableForeignKeyConstraints();
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_table_id_foreign');
            $table->dropColumn('table_id');
            $table->dropColumn('table_joined_at');
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->string('old_customers');
            $table->string('new_customers');
            $table->string('is_fetchable');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });
    }
}
