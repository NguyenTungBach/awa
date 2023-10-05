<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBalanceTempToCashInStatisticalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_in_statisticals', function (Blueprint $table) {
            $table->decimal('balance_temp', 15)->default(0)->after('month_line');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_in_statisticals', function (Blueprint $table) {
            $table->dropColumn('balance_temp');
        });
    }
}
