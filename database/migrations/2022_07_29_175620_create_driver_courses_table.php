<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_courses', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_id');
            $table->integer('course_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('breark_time');
            $table->dateTime('date');
            $table->integer('status')->nullable()->comment('1: on, 2: off');
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
        Schema::dropIfExists('driver_courses');
    }
}
