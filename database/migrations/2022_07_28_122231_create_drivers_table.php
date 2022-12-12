<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('flag')->nullable()->comment('lead|full|part');
            $table->string('driver_code');
            $table->string('driver_name');
            $table->string('grade')->default('0');
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->string('birth_day');
            $table->text('note')->nullable();
            $table->string('working_day')->nullable();
            $table->string('day_of_week')->nullable();
            $table->string('working_time')->nullable();
            $table->string('status')->nullable()->comment('on|off');
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
        Schema::dropIfExists('drivers');
    }
}
