<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('flag', 10)->default("no")->comment('yes|no');
            $table->string('pot', 10)->default("no")->comment('yes|no');
            $table->string('course_code');
            $table->string('course_name');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('break_time');
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->string('group')->nullable();
            $table->float('point')->comment('rate');
            $table->string('status')->nullable()->comment('on|off');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
