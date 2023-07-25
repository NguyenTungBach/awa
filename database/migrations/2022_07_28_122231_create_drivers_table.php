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
            $table->id();
            $table->integer('type')->comment('1: manager, 2: full-time, 3: part-time, 4: associate company');
            $table->string('driver_code', 15)->unique();
            $table->string('driver_name', 20);
            $table->dateTime('start_date')->comment('day join company');
            $table->dateTime('end_date')->nullable()->comment('retirement');
            $table->string('car', 20)->nullable();
            $table->text('note')->nullable();
            $table->string('status')->nullable()->comment('1:on, 2: off')->default(1);
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
