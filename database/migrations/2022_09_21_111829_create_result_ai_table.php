<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultAiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_ais', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('driver_code')->nullable();
            $table->string('course_code')->nullable();
            $table->string('date')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->string('result_ai')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('break_time')->nullable();
            $table->string('order_number')->nullable();
            $table->string('color')->nullable();
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
        Schema::dropIfExists('result_ais');
    }
}
