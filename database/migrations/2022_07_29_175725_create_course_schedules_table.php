<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 11);
            $table->string('course_name');
            $table->string('schedule_date');
            $table->string('status')->default('on')->comment('on|off');
            $table->string('lunar_jp')->nullable();

            $table->timestamps();
            $table->unique(['course_code', 'schedule_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_schedules');
    }
}
