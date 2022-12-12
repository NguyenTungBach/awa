<?php

namespace App\Console\Commands;

use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CronDeleteDriverCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cronDeleteDriverCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command delete driver delete 2 month';

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
            $timeDelete = Carbon::now()->subMonths(2);
            Driver::whereYear(Driver::DRIVER_END_DATE, $timeDelete->year)
                ->whereMonth(Driver::DRIVER_END_DATE, $timeDelete->month)
                ->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
