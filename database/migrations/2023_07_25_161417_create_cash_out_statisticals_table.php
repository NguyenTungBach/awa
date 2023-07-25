<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashOutStatisticalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_out_statisticals', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_id');
            $table->integer('month_line');
            $table->decimal('balance_previous_month', 15)->default(0);
            $table->decimal('payable_this_month', 15)->default(0);
            $table->decimal('total_cash_out_current', 15)->default(0);
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
        Schema::dropIfExists('cash_out_statisticals');
    }
}
