<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('file_name')->nullable();
            $table->string('file_code')->nullable();
            $table->string('type')->nullable();
            $table->string('date_time')->nullable();
            $table->string('path')->nullable();
            $table->string('model')->nullable();
            $table->string('status')->nullable();
            $table->string('flag')->nullable()->comment('yes|no')->default('no');
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('files');
    }
}
