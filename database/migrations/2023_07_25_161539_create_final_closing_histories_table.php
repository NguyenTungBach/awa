<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalClosingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_closing_histories', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('final closing date');
            $table->date('month_year')->comment('click button final closing for month');
            $table->integer('type')->comment('1: cash in, 2: cash out');
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
        Schema::dropIfExists('final_closing_histories');
    }
}
