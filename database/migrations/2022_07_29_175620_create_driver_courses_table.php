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
            $table->bigInteger('id')->autoIncrement();
            $table->string('driver_code')->nullable();
            $table->string('course_code')->nullable();
            $table->string('is_checked', 10)->default('no')->comment('yes|no');;
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
        Schema::dropIfExists('driver_courses');
    }
}
