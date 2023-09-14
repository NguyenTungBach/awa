<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('driver_id')->after('customer_id');
            $table->string('item_name', 20)->nullable()->after('arrival_place');
            $table->decimal('quantity', 15)->default(0)->after('item_name');
            $table->decimal('price', 15)->default(0)->after('quantity');
            $table->decimal('weight', 15)->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('driver_id');
            $table->dropColumn('item_name');
            $table->dropColumn('quantity');
            $table->dropColumn('price');
            $table->dropColumn('weight');
        });
    }
}
