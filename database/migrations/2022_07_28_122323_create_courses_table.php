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
            $table->id();
            $table->integer('customer_id');
            $table->string('course_name', 20);
            $table->date('ship_date');
            $table->time('start_date');
            $table->time('end_date');
            $table->time('break_time');
            $table->string('departure_place', 20);
            $table->string('arrival_place', 20);
            $table->decimal('ship_fee', 15);
            $table->decimal('associate_company_fee', 15)->default(0);
            $table->decimal('expressway_fee', 15)->default(0);
            $table->decimal('commission', 15)->default(0);
            $table->decimal('meal_fee', 15)->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
