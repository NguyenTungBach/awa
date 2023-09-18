<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloumnDriverIdsToFinalClosingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('final_closing_histories', function (Blueprint $table) {
            $table->json('driver_ids')->after('month_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('final_closing_histories', function (Blueprint $table) {
            $table->dropColumn('driver_ids)');
        });
    }
}
