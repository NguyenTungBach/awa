<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_code', 15)->unique();
            $table->string('user_name', 20);
            $table->string('password');
            $table->string('role')->comment('admin|driver');
            $table->string('jwt_active')->nullable();
            $table->remember_token();
            $table->integer('status')->nullable()->comment('1: on, 2:off');
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
