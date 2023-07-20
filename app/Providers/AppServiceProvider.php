<?php

namespace App\Providers;


use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\DriverCourseRepositoryInterface;
use App\Repositories\Contracts\DriverRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Repository\BaseRepository;
use Repository\AuthRepository;

use Repository\CalendarRepository;
use Repository\CourseRepository;
use Repository\DriverCourseRepository;
use Repository\DriverRepository;
use Laravel\Dusk\DuskServiceProvider;
use Repository\ReportRepository;

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
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(DriverCourseRepositoryInterface::class, DriverCourseRepository::class);
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);

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
