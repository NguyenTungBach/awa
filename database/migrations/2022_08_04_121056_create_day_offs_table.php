<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_offs', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('driver_code')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('date')->nullable();
            $table->string('type')->nullable();
            $table->string('has_codes')->nullable();
            $table->string('color')->nullable();
            $table->string('lunar_jp')->nullable();
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
        Schema::dropIfExists('day_offs');
    }
}
