<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Report;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = Driver::select('id', 'driver_code', 'driver_name', 'flag')
            ->with('driverCourse')
            ->where('status', Driver::DRIVER_STATUS_WORK)
            ->get()->toArray();
        if (!Report::first()){
            $fake = Factory::create();
            $drivers = [];
            foreach ($drivers as $d) {
                $ran = [Report::STATUS_MONTH, Report::STATUS_FIX];
                $status = $ran[array_rand($ran, 1)];



                if ($status == Report::STATUS_MONTH) {
                    $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                    $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
                } else {
                    $endDate = \Carbon\Carbon::createFromDate(date('Y'), date('m'), 21)->toDateString();
                    $startDate = \Carbon\Carbon::createFromDate(date('Y'), date('m'), 21)->subMonth()->toDateString();

                }
                $ran2 = [0, 0.25, 0.5, 0.75];
                Report::create([
                    Report::DRIVER_CODE => $d['driver_code'],
                    Report::DRIVER_NAME => $d['driver_name'],
                    Report::TOTAL_TIME => $fake->numberBetween(180, 250) + $ran2[array_rand($ran2, 1)],
                    Report::DRIVING_TIME =>  $fake->numberBetween(180, 250) + $ran2[array_rand($ran2, 1)],
                    Report::OVER_TIME =>  $fake->numberBetween(0, 60) + $ran2[array_rand($ran2, 1)],
                    Report::WORKING_DAYS =>  $fake->numberBetween(16, 20),
                    Report::DAYS_OFF =>  $fake->numberBetween(8, 12),
                    Report::PAID_HOLIDAYS =>  $fake->numberBetween(0, 3),
                    Report::MAX_TOTAL_TIME_DAY =>  $fake->numberBetween(9, 15),
                    Report::MAX_DRIVING_TIME_DAY =>  $fake->numberBetween(8, 12),
                    Report::WORKING_OVER_15_HOUR_DAY =>  $fake->numberBetween(2, 7),
                    Report::STATUS =>  $status,
                    Report::START_DATE =>  $startDate,
                    Report::END_DATE =>  $endDate,
                ]);
            }

        }
    }
}
