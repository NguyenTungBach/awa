<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CronChangeStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cronChangeStatusCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'chang status driver and couse with end date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $timeNow = Carbon::now();
            $timeFirst = $timeNow->subDay(1)->toDateString();
            $updateStatusDriver = Driver::whereDate(Driver::DRIVER_END_DATE,$timeFirst)->update(['status' => 'off']);
            $updateStatusCourse = Course::whereDate(Course::COURSE_END_DATE,$timeFirst)->update(['status' => 'off']);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
