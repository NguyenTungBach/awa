<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryClosingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_closing_histories', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('temporary closing date');
            $table->string('month_year')->comment('Y-m, click button temporary closing for month');
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
        Schema::dropIfExists('temporary_closing_histories');
    }
}
