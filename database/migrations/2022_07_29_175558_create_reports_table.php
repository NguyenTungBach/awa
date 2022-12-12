<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('driver_code');
            $table->string('driver_name');
            $table->string('total_time', 191);
            $table->string('driving_time', 191);
            $table->string('over_time', 191);
            $table->string('working_days', 255);
            $table->string('days_off', 255);
            $table->string('paid_holidays', 255);
            $table->char('max_total_time_day', 5);
            $table->char('max_driving_time_day', 5);
            $table->char('working_over_day', 2);

            $table->string('status', 255);
            $table->string('start_date', 255);
            $table->string('end_date', 255);

            $table->timestamps();

            $table->unique(['driver_code', 'status', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
