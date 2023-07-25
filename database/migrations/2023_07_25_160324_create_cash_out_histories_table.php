<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashOutHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_out_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_id');
            $table->integer('cash_out_id');
            $table->integer('type')->comment('1: update, 2:delete');
            $table->decimal('cash_out', 15);
            $table->integer('payment_method')->comment('1: 銀行振込 - ngân hàng ck, 2: 口座振替 - bưu điện ck');
            $table->date('payment_date');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('cash_out_histories');
    }
}
