<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 15)->unique();
            $table->string('customer_name', 20);
            $table->integer('closing_date')->comment('1: 15th, 2: 20th, 3: 25th, 4: last day of month');
            $table->string('person_charge', 20);
            $table->string('post_code', 8);
            $table->string('address', 100);
            $table->string('phone', 11);
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
        Schema::dropIfExists('customers');
    }
}
