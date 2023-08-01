<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashInStatisticalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_in_statisticals', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('month_line');
            $table->decimal('balance_previous_month', 15)->default(0);
            $table->decimal('receivable_this_month', 15)->default(0);
            $table->decimal('total_cash_in_current', 15)->default(0);
            $table->integer('status')->nullable()->comment('1: on, 2: off')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_in_statisticals');
    }
}
