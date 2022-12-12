<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('course_parent_code');
            $table->string('course_child_code');
            $table->string('status');
            $table->timestamps();
            $table->unique(['course_parent_code', 'course_child_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_patterns');
    }
}
