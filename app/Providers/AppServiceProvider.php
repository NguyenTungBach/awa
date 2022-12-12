<?php

namespace App\Providers;


use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use App\Repositories\Contracts\CoursePatternRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use App\Repositories\Contracts\DriverRepositoryInterface;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\ShiftRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\NoticesRepositoryInterface;
use App\Repositories\Contracts\NoticeViewerRepositoryInterface;
use App\Repositories\Contracts\GroupChatRepositoryInterface;
use App\Repositories\Contracts\GroupChatUserRepositoryInterface;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\DayOffRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Repository\BaseRepository;
use Repository\AuthRepository;

use Repository\CalendarRepository;
use Repository\CoursePatternRepository;
use Repository\CourseRepository;
use Repository\CourseScheduleRepository;
use Repository\DriverCourseRepository;
use Repository\DriverRepository;
use Repository\FileRepository;
use Repository\DayOffRepository;
use Laravel\Dusk\DuskServiceProvider;
use Repository\ReportRepository;
use Repository\ShiftRepository;

class  AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CalendarRepositoryInterface::class, CalendarRepository::class);
        $this->app->bind(CoursePatternRepositoryInterface::class, CoursePatternRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseScheduleRepositoryInterface::class, CourseScheduleRepository::class);
        $this->app->bind(DriverCourseRepositoryInterface::class, DriverCourseRepository::class);
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(DayOffRepositoryInterface::class, DayOffRepository::class);
        $this->app->bind(ShiftRepositoryInterface::class, ShiftRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

        //Customer
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
