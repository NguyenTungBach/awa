<?php

namespace App\Console\Commands;

use App\Models\CoursePattern;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Course;

class DeleteCourseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cronDeleteCourseCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command delete course delete 2 month';

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
            $codeList = Course::whereYear(Course::COURSE_END_DATE, $timeDelete->year)
                ->whereMonth(Course::COURSE_END_DATE, $timeDelete->month)->pluck('course_name');
            if (!empty($codeList->all())) {
                foreach ($codeList->all() as $code) {
                    CoursePattern::where(CoursePattern::COURSE_PATTERN_PARENT_CODE, $code)
                                    ->orWhere(CoursePattern::COURSE_PATTERN_CHILD_CODE, $code)
                                    ->delete();
                }
            }
            Course::whereYear(Course::COURSE_END_DATE, $timeDelete->year)
                ->whereMonth(Course::COURSE_END_DATE, $timeDelete->month)
                ->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
