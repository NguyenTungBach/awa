<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\DayOff;
use App\Models\Driver;
use App\Repositories\Contracts\DayOffRepositoryInterface;
use App\Repositories\Contracts\DriverRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Repository\DayOffRepository;


class CronUpdateDriverDayOffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cronUpdateDriverDayOffCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command update list dayoff driver';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DayOffRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $timeUpdate = Carbon::now()->addMonth(12);
            $listDriver = Driver::where(Driver::DRIVER_STATUS, 'on')->whereNotNull(Driver::DRIVER_DAY_OF_WEEK)->get();
            foreach ($listDriver as $keyDriver => $valueDriver) {
                $dayOff = DayOff::where(DayOff::DAY_OFF_DRIVER_CODE, $valueDriver->driver_code)
                    ->whereYear(DayOff::DAY_OFF_DATE, $timeUpdate->year)
                    ->whereMonth(DayOff::DAY_OFF_DATE, $timeUpdate->month)
                    ->first();
                if (!$dayOff) {
                    $this->repository->createDayOff($valueDriver, 'cronUpdate');
                }
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
